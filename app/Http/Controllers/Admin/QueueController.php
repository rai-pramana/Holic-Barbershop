<?php

namespace App\Http\Controllers\Admin;

use App\Concerns\Sortable;
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
    use Sortable;

    /**
     * List all queues (filterable)
     */
    public function index(Request $request): View
    {
        Queue::expirePending();

        // Validasi filter tanggal lebih dulu — Carbon::parse atas input mentah = 500.
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'date'      => 'nullable|date',
        ]);

        $query = Queue::with(['customer', 'barber', 'service', 'branch']);

        // Filter whitelist: numerik untuk FK, enum untuk status.
        $branchFilter = $request->query('branch_id', '');
        if ($branchFilter !== '' && ctype_digit((string) $branchFilter)) {
            $query->where('queues.branch_id', $branchFilter);
        }
        $statusFilter = $request->query('status', '');
        if (in_array($statusFilter, ['pending', 'active', 'called', 'completed', 'skipped', 'expired'], true)) {
            $query->where('queues.status', $statusFilter);
        }
        $barberFilter = $request->query('barber_id', '');
        if ($barberFilter !== '' && ctype_digit((string) $barberFilter)) {
            $query->where('queues.barber_id', $barberFilter);
        }

        // Support both single date and date range (pakai nilai tervalidasi)
        if (! empty($validated['date_from']) && ! empty($validated['date_to'])) {
            $query->whereBetween('queues.created_at', [
                \Carbon\Carbon::parse($validated['date_from'])->startOfDay(),
                \Carbon\Carbon::parse($validated['date_to'])->endOfDay(),
            ]);
            $dateLabel = \Carbon\Carbon::parse($validated['date_from'])->isoFormat('D MMM YYYY')
                . ' — '
                . \Carbon\Carbon::parse($validated['date_to'])->isoFormat('D MMM YYYY');
        } elseif (! empty($validated['date_from'])) {
            $query->whereDate('queues.created_at', $validated['date_from']);
            $dateLabel = \Carbon\Carbon::parse($validated['date_from'])->isoFormat('dddd, D MMMM YYYY');
        } elseif (! empty($validated['date'])) {
            $query->whereDate('queues.created_at', $validated['date']);
            $dateLabel = \Carbon\Carbon::parse($validated['date'])->isoFormat('dddd, D MMMM YYYY');
        } else {
            $query->whereDate('queues.created_at', today());
            $dateLabel = now()->isoFormat('dddd, D MMMM YYYY');
        }

        [$query, $sort, $dir] = $this->applyQueueSort($query, $request);
        $queues   = $query->paginate(25)->withQueryString();
        $branches = Branch::where('is_active', true)->get();
        $barbers  = Barber::orderBy('name')->get();

        return view('admin.queues.index', compact('queues', 'branches', 'barbers', 'dateLabel', 'sort', 'dir'));
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
        // Pilihan cabang persisten: query > session > cabang pertama.
        // Aksi POST (call/complete/skip) redirect back() tanpa query,
        // jadi session menjaga pilihan tetap saat refresh maupun aksi loket.
        if ($request->filled('branch_id') && $branches->contains('id', (int) $request->branch_id)) {
            session(['manage_branch_id' => (int) $request->branch_id]);
        }
        $selectedBranch = $branches->firstWhere('id', session('manage_branch_id'))
            ?? $branches->first();

        // Sembunyikan cabang yang sudah tutup dari pemilih; karyawan fokus
        // melayani cabang yang buka. Cabang tersimpan yang keburu tutup tetap
        // ditampilkan (dengan penanda) agar antrean tersisa bisa diselesaikan.
        $openBranches = $branches->filter(fn(Branch $b) => $b->isOpen())->values();
        $selectedIsClosed = $selectedBranch && ! $selectedBranch->isOpen();

        $barbers = [];
        if ($selectedBranch) {
            // Tampilkan barber tersedia + barber tidak tersedia yang MASIH
            // punya antrean aktif hari ini (agar bisa diselesaikan di loket).
            $barbers = Barber::where('branch_id', $selectedBranch->id)
                ->where(function ($q) {
                    $q->where('is_available', true)
                      ->orWhereHas('queues', function ($qq) {
                          $qq->whereDate('queues.created_at', today())
                             ->whereIn('status', ['active', 'called', 'pending']);
                      });
                })
                ->with(['queues' => function ($q) {
                    $q->whereDate('queues.created_at', today())
                      ->whereIn('status', ['active', 'called', 'pending'])
                      ->with(['customer', 'service'])
                      ->orderByRaw("FIELD(status, 'called', 'active', 'pending')")
                      ->orderBy('id');
                }])
                ->get();
        }

        // Recent check-ins today (for merged check-in panel)
        $recent = Queue::with(['customer', 'branch'])
            ->whereDate('queues.created_at', today())
            ->whereNotNull('checked_in_at')
            ->orderByDesc('checked_in_at')
            ->take(8)
            ->get();

        return view('admin.queues.manage', compact('branches', 'openBranches', 'selectedBranch', 'selectedIsClosed', 'barbers', 'recent'));
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
            \Illuminate\Support\Facades\Bus::dispatchSync(new SendQueuePushNotification($queue->id, 'called'));
            \Illuminate\Support\Facades\Log::info('Push called dispatched', ['queue_id' => $queue->id, 'customer_id' => $queue->customer_id, 'subs' => \App\Models\PushSubscription::where('user_id', $queue->customer_id)->count()]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push called dispatch failed', ['queue_id' => $queue->id, 'error' => $e->getMessage()]);
        }

        // Beritahu antrean yang kini tinggal ≤3 di depan (sekali per antrean)
        self::notifyNewlyNear($queue);

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
            \Illuminate\Support\Facades\Bus::dispatchSync(new SendQueuePushNotification($queue->id, 'completed'));
        } catch (\Throwable $e) {
            // Silent — push failure must not block queue management
        }

        // Beritahu antrean yang kini tinggal ≤3 di depan (sekali per antrean)
        self::notifyNewlyNear($queue);

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
            \Illuminate\Support\Facades\Bus::dispatchSync(new SendQueuePushNotification($queue->id, 'skipped'));
        } catch (\Throwable $e) {
            // Silent — push failure must not block queue management
        }

        // Beritahu antrean yang kini tinggal ≤3 di depan (sekali per antrean)
        self::notifyNewlyNear($queue);

        return back()->with('success', "⚠️ Antrean #{$queue->queue_number} telah dilewati.");
    }

    /**
     * Kirim push "segera giliran" bertingkat (3 → 2 → 1 di depan) ke antrean
     * yang turun level akibat perubahan $changed. Tiap level dikirim sekali
     * (notified_near_level); kegagalan push tidak menggagalkan aksi loket.
     */
    private static function notifyNewlyNear(Queue $changed): void
    {
        try {
            foreach (Queue::newlyNear($changed) as [$candidate, $level]) {
                $candidate->update(['notified_near_at' => now(), 'notified_near_level' => $level]);
                \Illuminate\Support\Facades\Bus::dispatchSync(
                    new SendQueuePushNotification($candidate->id, 'near', $level)
                );
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Push near failed (non-fatal)', [
                'queue_id' => $changed->id, 'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * AJAX: token QR check-in cabang saat ini (QR berputar tiap 60 detik).
     */
    public function checkinToken(Branch $branch): JsonResponse
    {
        return response()->json([
            'url' => route('customer.checkin.scan', $branch->id)
                . '?t=' . \App\Http\Controllers\Customer\QueueController::checkinToken($branch->id),
        ]);
    }

    /**
     * AJAX: Poll for new queues — used by admin notification system
     */
    public function notificationPoll(): JsonResponse
    {
        $today = today();

        $pending   = Queue::whereDate('queues.created_at', $today)->where('status', 'pending')->count();
        $active    = Queue::whereDate('queues.created_at', $today)->where('status', 'active')->count();
        $called    = Queue::whereDate('queues.created_at', $today)->where('status', 'called')->count();
        $completed = Queue::whereDate('queues.created_at', $today)->where('status', 'completed')->count();
        $total     = Queue::whereDate('queues.created_at', $today)->count();

        // Latest queue for notification detail
        $latest = Queue::with('customer', 'branch')
            ->whereDate('queues.created_at', $today)
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

    /**
     * Sorting Riwayat Antrean — termasuk kolom relasi via join.
     * Whitelist ketat: kunci sort dipetakan ke ekspresi aman, bukan input mentah.
     */
    private function applyQueueSort($query, Request $request): array
    {
        $map = [
            'queue_number' => 'queues.queue_number',
            'status'       => 'queues.status',
            'created_at'   => 'queues.created_at',
            'completed_at' => 'queues.completed_at',
            // Kolom relasi — butuh join (LEFT agar barber null tetap muncul)
            'customer' => 'customer_sort',
            'barber'   => 'barbers.name',
            'service'  => 'services.name',
            'price'    => 'services.price',
            'branch'   => 'branches.name',
        ];

        $sort = $request->query('sort', 'created_at');
        $dir  = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        if (! array_key_exists($sort, $map)) {
            $sort = 'created_at';
            $dir  = 'desc';
        }

        $query->select('queues.*')
            ->leftJoin('users', 'users.id', '=', 'queues.customer_id')
            ->leftJoin('barbers', 'barbers.id', '=', 'queues.barber_id')
            ->leftJoin('services', 'services.id', '=', 'queues.service_id')
            ->leftJoin('branches', 'branches.id', '=', 'queues.branch_id');

        if ($map[$sort] === 'customer_sort') {
            // Walk-in pakai guest_name, akun pakai users.name
            $query->orderByRaw('COALESCE(NULLIF(queues.guest_name, ""), users.name) ' . $dir);
        } else {
            $query->orderBy($map[$sort], $dir);
        }
        // Tiebreaker id: cegah baris bocor/duplikat antar halaman.
        $query->orderBy('queues.id', $dir);

        return [$query, $sort, $dir];
    }
}
