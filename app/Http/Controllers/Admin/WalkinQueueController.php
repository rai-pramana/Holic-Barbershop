<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalkinQueueController extends Controller
{
    /**
     * Show walk-in queue form
     */
    public function create(Request $request): View
    {
        $branches = Branch::where('is_active', true)->get();

        $selectedBranch = $request->filled('branch_id')
            ? Branch::findOrFail($request->branch_id)
            : $branches->first();

        $services = collect();
        $barbers  = collect();

        if ($selectedBranch) {
            $services = Service::where('branch_id', $selectedBranch->id)
                ->where('is_active', true)
                ->get();

            $barbers = Barber::where('branch_id', $selectedBranch->id)
                ->where('is_available', true)
                ->get()
                ->map(function ($barber) {
                    $barber->pending_count = $barber->getPendingQueueCount();
                    return $barber;
                })
                ->sortBy('pending_count')
                ->values();
        }

        return view('admin.queues.walkin', compact('branches', 'selectedBranch', 'services', 'barbers'));
    }

    /**
     * Store a walk-in queue (no customer account needed)
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id'  => 'required|exists:branches,id',
            'service_id' => 'required|exists:services,id',
            'barber_id'  => 'nullable|exists:barbers,id',
            'guest_name' => 'required|string|max:100',
            'guest_phone'=> 'nullable|string|max:20',
            'notes'      => 'nullable|string|max:500',
        ]);

        $branch  = Branch::findOrFail($request->branch_id);
        $service = Service::where('id', $request->service_id)
            ->where('branch_id', $branch->id)
            ->firstOrFail();

        // Select or auto-assign barber
        if ($request->filled('barber_id')) {
            $barber = Barber::where('id', $request->barber_id)
                ->where('branch_id', $branch->id)
                ->where('is_available', true)
                ->firstOrFail();
        } else {
            $barber = Barber::where('branch_id', $branch->id)
                ->where('is_available', true)
                ->get()
                ->sortBy(fn($b) => $b->getPendingQueueCount())
                ->first();

            if (!$barber) {
                return back()->withInput()->with('error', 'Tidak ada barber yang tersedia saat ini.');
            }
        }

        $queue = null;
        \Illuminate\Support\Facades\DB::transaction(function () use (
            $branch, $barber, $service, $request, &$queue
        ) {
            $queueNumber = $branch->getNextQueueNumber();

            // Walk-in queue: auto-active (no check-in needed, admin already validated)
            $queue = Queue::create([
                'queue_number' => $queueNumber,
                'customer_id'  => null,           // No account
                'guest_name'   => trim($request->guest_name),
                'guest_phone'  => $request->guest_phone ? trim($request->guest_phone) : null,
                'barber_id'    => $barber->id,
                'service_id'   => $service->id,
                'branch_id'    => $branch->id,
                'status'       => Queue::STATUS_ACTIVE, // Auto-validated
                'notes'        => $request->notes,
                'checked_in_at'=> now(),
            ]);
        });

        return redirect()->route('admin.queues.manage', ['branch_id' => $branch->id])
            ->with('success', "✅ Antrean walk-in #{$queue->queue_number} untuk {$request->guest_name} berhasil dibuat dan otomatis tervalidasi.");
    }
}
