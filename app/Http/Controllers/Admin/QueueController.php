<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendQueuePushNotification;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueController extends Controller
{
    /**
     * List all queues (filterable)
     */
    public function index(Request $request): View
    {
        Queue::expirePending();

        $query = Queue::with(['customer', 'barber', 'service', 'branch'])->latest();

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', today());
        }

        $queues   = $query->paginate(20)->withQueryString();
        $branches = Branch::where('is_active', true)->get();

        return view('admin.queues.index', compact('queues', 'branches'));
    }

    /**
     * Show detail of a single queue
     */
    public function show(Queue $queue): View
    {
        $queue->load(['customer', 'barber', 'service', 'branch']);
        return view('admin.queues.show', compact('queue'));
    }

    /**
     * Kelola Antrean — board per barber (replaces barber dashboard)
     */
    public function manage(Request $request): View
    {
        Queue::expirePending();
        Queue::autoSkipCalled();

        $branches = Branch::where('is_active', true)->with('barbers')->get();
        $selectedBranch = $request->filled('branch_id')
            ? Branch::find($request->branch_id)
            : $branches->first();

        $barbers = [];
        if ($selectedBranch) {
            $barbers = Barber::where('branch_id', $selectedBranch->id)
                ->where('is_available', true)
                ->with(['queues' => function ($q) {
                    $q->whereDate('created_at', today())
                      ->whereIn('status', ['active', 'called', 'pending'])
                      ->with(['customer', 'service'])
                      ->orderByRaw("FIELD(status, 'called', 'active', 'pending')")
                      ->orderBy('id');
                }])
                ->get();
        }

        return view('admin.queues.manage', compact('branches', 'selectedBranch', 'barbers'));
    }

    /**
     * Call the next active queue (Active → Called)
     */
    public function call(Queue $queue): RedirectResponse
    {
        if ($queue->status !== Queue::STATUS_ACTIVE) {
            return back()->with('error', 'Hanya antrean yang sudah hadir (tervalidasi) yang dapat dipanggil.');
        }

        $queue->update([
            'status'    => Queue::STATUS_CALLED,
            'called_at' => now(),
        ]);

        // Send push notification to customer (sync — no queue worker needed)
        try {
            dispatchSync(new SendQueuePushNotification($queue->id, 'called'));
        } catch (\Throwable $e) {
            // Silent — push failure must not block queue management
        }

        return back()->with('success', "🔔 Antrean #{$queue->queue_number} ({$queue->customer_name}) berhasil dipanggil.");
    }

    /**
     * Mark queue as completed (Called → Completed)
     */
    public function complete(Queue $queue): RedirectResponse
    {
        if ($queue->status !== Queue::STATUS_CALLED) {
            return back()->with('error', 'Antrean harus dalam status Dipanggil untuk diselesaikan.');
        }

        $queue->update([
            'status'       => Queue::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // Send push notification to customer (sync — no queue worker needed)
        try {
            dispatchSync(new SendQueuePushNotification($queue->id, 'completed'));
        } catch (\Throwable $e) {
            // Silent — push failure must not block queue management
        }

        return back()->with('success', "✅ Antrean #{$queue->queue_number} telah selesai.");
    }

    /**
     * Skip the queue — customer not present (Called/Active → Skipped)
     */
    public function skip(Queue $queue): RedirectResponse
    {
        if (!in_array($queue->status, [Queue::STATUS_CALLED, Queue::STATUS_ACTIVE])) {
            return back()->with('error', 'Hanya antrean aktif atau dipanggil yang dapat dilewati.');
        }

        $queue->update(['status' => Queue::STATUS_SKIPPED]);

        // Send push notification to customer (sync — no queue worker needed)
        try {
            dispatchSync(new SendQueuePushNotification($queue->id, 'skipped'));
        } catch (\Throwable $e) {
            // Silent — push failure must not block queue management
        }

        return back()->with('success', "⚠️ Antrean #{$queue->queue_number} telah dilewati.");
    }

    /**
     * AJAX: poll queue board for live updates on manage page
     */
    public function poll(Request $request): JsonResponse
    {
        $branchId = $request->branch_id;

        $data = Barber::where('branch_id', $branchId)
            ->where('is_available', true)
            ->with(['queues' => function ($q) {
                $q->whereDate('created_at', today())
                  ->whereIn('status', ['active', 'called', 'pending'])
                  ->with(['customer', 'service'])
                  ->orderByRaw("FIELD(status, 'called', 'active', 'pending')")
                  ->orderBy('id');
            }])
            ->get()
            ->map(fn($b) => [
                'id'   => $b->id,
                'name' => $b->name,
                'queues' => $b->queues->map(fn($q) => [
                    'id'           => $q->id,
                    'queue_number' => $q->queue_number,
                    'status'       => $q->status,
                    'status_label' => $q->status_label,
                    'customer'     => $q->customer_name,
                    'service'      => $q->service->name,
                ]),
            ]);

        return response()->json($data);
    }

    /**
     * AJAX: Poll for new queues — used by admin notification system
     */
    public function notificationPoll(): JsonResponse
    {
        $today = today();

        $pending   = Queue::whereDate('created_at', $today)->where('status', 'pending')->count();
        $active    = Queue::whereDate('created_at', $today)->where('status', 'active')->count();
        $called    = Queue::whereDate('created_at', $today)->where('status', 'called')->count();
        $completed = Queue::whereDate('created_at', $today)->where('status', 'completed')->count();
        $total     = Queue::whereDate('created_at', $today)->count();

        // Latest queue for notification detail
        $latest = Queue::with('customer', 'branch')
            ->whereDate('created_at', $today)
            ->latest()
            ->first();

        return response()->json([
            'total'     => $total,
            'pending'   => $pending,
            'active'    => $active,
            'called'    => $called,
            'completed' => $completed,
            'latest'    => $latest ? [
                'id'           => $latest->id,
                'queue_number' => $latest->queue_number,
                'customer'     => $latest->customer_name,
                'branch'       => $latest->branch->name ?? '-',
                'status'       => $latest->status,
                'created_at'   => $latest->created_at->toISOString(),
            ] : null,
        ]);
    }
}
