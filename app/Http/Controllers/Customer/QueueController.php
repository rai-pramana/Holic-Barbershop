<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * Customer dashboard — show active queue or branch selection
     */
    public function dashboard(): View
    {
        Queue::expirePending();

        $user = Auth::user();

        // Get all active queues for this customer across all branches
        $activeQueues = Queue::with(['branch', 'barber', 'service'])
            ->where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'active', 'called'])
            ->whereDate('created_at', today())
            ->get();

        $branches = Branch::where('is_active', true)->get();

        return view('customer.dashboard', compact('activeQueues', 'branches'));
    }

    /**
     * Show form to take a queue at a specific branch
     */
    public function take(Branch $branch): View
    {
        $user = Auth::user();

        // Check for existing active queue at this branch
        $existingQueue = $user->activeQueue($branch->id);
        if ($existingQueue) {
            return redirect()->route('customer.queue.status', $existingQueue)
                ->with('info', 'Anda sudah memiliki antrean aktif di cabang ini.');
        }

        $services = Service::where('branch_id', $branch->id)
            ->where('is_active', true)
            ->get();

        $barbers = Barber::query()
            ->where('branch_id', $branch->id)
            ->where('is_available', true)
            ->get()
            ->map(function ($barber) {
                $stats = $barber->getQueueStats();
                $barber->pending_count          = $stats['pending_count'];
                $barber->current_serving        = $stats['current_serving'];
                $barber->estimated_wait_minutes = $stats['estimated_wait_minutes'];
                return $barber;
            })
            ->sortBy('pending_count');

        return view('customer.queue.take', compact('branch', 'services', 'barbers'));
    }

    /**
     * Store a new queue
     */
    public function store(Request $request, Branch $branch): RedirectResponse
    {
        $user = Auth::user();

        // Prevent double queue
        $existingQueue = $user->activeQueue($branch->id);
        if ($existingQueue) {
            return redirect()->route('customer.queue.status', $existingQueue)
                ->with('info', 'Anda sudah memiliki antrean aktif di cabang ini.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id'  => 'nullable|exists:barbers,id',
            'notes'      => 'nullable|string|max:500',
        ]);

        // Validate service belongs to this branch
        $service = Service::where('id', $request->service_id)
            ->where('branch_id', $branch->id)
            ->firstOrFail();

        // Select barber: manual or auto (fastest)
        if ($request->filled('barber_id')) {
            $barber = Barber::where('id', $request->barber_id)
                ->where('branch_id', $branch->id)
                ->where('is_available', true)
                ->firstOrFail();
        } else {
            // Auto-assign: barber with fewest pending/active queues
            $barber = Barber::query()
                ->where('branch_id', $branch->id)
                ->where('is_available', true)
                ->get()
                ->sortBy(fn($b) => $b->getPendingQueueCount())
                ->first();

            if (!$barber) {
                return back()->with('error', 'Tidak ada barber yang tersedia saat ini.');
            }
        }

        $queueNumber = null;
        $queue = null;

        \Illuminate\Support\Facades\DB::transaction(function () use (
            $user, $branch, $barber, $service, $request, &$queueNumber, &$queue
        ) {
            $queueNumber = $branch->getNextQueueNumber();

            $queue = Queue::create([
                'queue_number' => $queueNumber,
                'customer_id'  => $user->id,
                'barber_id'    => $barber->id,
                'service_id'   => $service->id,
                'branch_id'    => $branch->id,
                'status'       => Queue::STATUS_PENDING,
                'notes'        => $request->notes,
                'expired_at'   => now()->addMinutes(60),
            ]);
        });

        return redirect()->route('customer.queue.status', $queue)
            ->with('success', "Antrean berhasil dibuat! Nomor antrean Anda: {$queueNumber}");
    }

    /**
     * Show queue status page (with auto-polling)
     */
    public function status(Queue $queue): View
    {
        // Ensure the queue belongs to the authenticated user
        if ($queue->customer_id !== Auth::id()) {
            abort(403);
        }

        Queue::expirePending();

        $queue->load(['branch', 'barber', 'service']);

        // Queues ahead in same barber queue (with actual service durations)
        $aheadQueues = Queue::where('barber_id', $queue->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->whereDate('created_at', today())
            ->where('id', '<', $queue->id)
            ->with('service')
            ->get();

        $queuesAhead  = $aheadQueues->whereIn('status', ['active', 'called'])->count();
        $pendingAhead = $aheadQueues->where('status', 'pending')->count();

        // Calculate wait: sum each queue's own service duration
        $waitMinutes = 0;
        foreach ($aheadQueues as $aq) {
            $dur = $aq->service?->duration_minutes ?? 30;

            if (in_array($aq->status, ['called', 'active'])) {
                // Already being served — subtract elapsed time since check-in
                $startedAt = $aq->checked_in_at ?? $aq->called_at ?? $aq->created_at;
                $elapsed   = max(0, Carbon::parse($startedAt)->diffInMinutes(now()));
                $remaining = max(0, $dur - $elapsed);
                $waitMinutes += $remaining;
            } else {
                // Pending — full duration still to come
                $waitMinutes += $dur;
            }
        }

        // Currently serving queue number at same barber
        $currentServing = Queue::where('barber_id', $queue->barber_id)
            ->whereIn('status', ['called', 'active'])
            ->whereDate('created_at', today())
            ->orderBy('id', 'asc')
            ->value('queue_number');

        return view('customer.queue.status', compact('queue', 'queuesAhead', 'pendingAhead', 'waitMinutes', 'currentServing'));
    }

    /**
     * AJAX: Poll queue status for real-time updates
     */
    public function poll(Queue $queue): JsonResponse
    {
        if ($queue->customer_id !== Auth::id()) {
            abort(403);
        }

        Queue::expirePending();
        $queue->refresh();
        $queue->load(['barber', 'service']);

        $aheadQueues = Queue::where('barber_id', $queue->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->whereDate('created_at', today())
            ->where('id', '<', $queue->id)
            ->with('service')
            ->get();

        $queuesAhead  = $aheadQueues->whereIn('status', ['active', 'called'])->count();
        $pendingAhead = $aheadQueues->where('status', 'pending')->count();

        $waitMinutes = 0;
        foreach ($aheadQueues as $aq) {
            $dur = $aq->service?->duration_minutes ?? 30;
            if (in_array($aq->status, ['called', 'active'])) {
                $startedAt   = $aq->checked_in_at ?? $aq->called_at ?? $aq->created_at;
                $elapsed     = max(0, Carbon::parse($startedAt)->diffInMinutes(now()));
                $waitMinutes += max(0, $dur - $elapsed);
            } else {
                $waitMinutes += $dur;
            }
        }

        $position = Queue::where('barber_id', $queue->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->whereDate('created_at', today())
            ->where('id', '<=', $queue->id)
            ->count();

        $currentServing = Queue::where('barber_id', $queue->barber_id)
            ->whereIn('status', ['called', 'active'])
            ->whereDate('created_at', today())
            ->orderBy('id', 'asc')
            ->value('queue_number');

        return response()->json([
            'status'          => $queue->status,
            'status_label'    => $queue->status_label,
            'queues_ahead'    => $queuesAhead,
            'pending_ahead'   => $pendingAhead,
            'wait_minutes'    => $waitMinutes,
            'position'        => $position,
            'current_serving' => $currentServing,
            'called_at'       => $queue->called_at?->toIso8601String(),
            'completed_at'    => $queue->completed_at?->toIso8601String(),
        ]);
    }

    /**
     * Show queue history for the authenticated customer
     */
    public function history(Request $request): View
    {
        $user = Auth::user();

        $query = Queue::with(['branch', 'barber', 'service'])
            ->where('customer_id', $user->id)
            ->whereIn('status', ['completed', 'skipped', 'expired'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $histories = $query->paginate(10)->withQueryString();

        return view('customer.queue.history', compact('histories'));
    }

    public function scanCheckin(Branch $branch): RedirectResponse
    {
        Queue::expirePending();

        $user = Auth::user();

        // Find today's pending queue at this branch
        $queue = Queue::where('customer_id', $user->id)
            ->where('branch_id', $branch->id)
            ->where('status', Queue::STATUS_PENDING)
            ->whereDate('created_at', today())
            ->latest()
            ->first();

        if (! $queue) {
            // Check if already validated today
            $activeQueue = Queue::where('customer_id', $user->id)
                ->where('branch_id', $branch->id)
                ->whereIn('status', [Queue::STATUS_ACTIVE, Queue::STATUS_CALLED])
                ->whereDate('created_at', today())
                ->first();

            if ($activeQueue) {
                return redirect()->route('customer.queue.status', $activeQueue)
                    ->with('info', '✅ Anda sudah tervalidasi. Silakan tunggu dipanggil.');
            }

            return redirect()->route('customer.dashboard')
                ->with('error', "Tidak ada antrean aktif di cabang {$branch->name} hari ini. Silakan ambil antrean terlebih dahulu.");
        }

        // Auto validate
        $queue->update([
            'status'        => Queue::STATUS_ACTIVE,
            'checked_in_at' => now(),
        ]);

        // Send push notification (confirmed check-in)
        try {
            dispatchSync(new \App\Jobs\SendQueuePushNotification($queue->id, 'active'));
        } catch (\Throwable $e) {
            // Silent — notification failure should not block check-in
        }

        return redirect()->route('customer.queue.status', $queue)
            ->with('success', "✅ Check-in berhasil di {$branch->name}! Nomor antrean Anda: {$queue->queue_number}. Silakan tunggu dipanggil.");
    }
}
