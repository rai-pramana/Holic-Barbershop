<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendQueuePushNotification;
use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function search(Request $request): RedirectResponse
    {
        $request->validate([
            'queue_number' => 'required|string',
            'branch_id'    => 'nullable|exists:branches,id',
        ]);

        $query = Queue::with(['customer', 'barber', 'service', 'branch'])
            ->where('queues.queue_number', strtoupper(trim($request->queue_number)))
            ->whereDate('queues.created_at', today());

        if ($request->filled('branch_id')) {
            $query->where('queues.branch_id', $request->branch_id);
        }

        $queue = $query->first();

        if (! $queue) {
            return back()->with('error', "Antrean '{$request->queue_number}' tidak ditemukan hari ini.");
        }

        return redirect()->route('admin.checkin.confirm', $queue->validation_token);
    }

    public function confirm(string $token): View
    {
        $queue = Queue::with(['customer', 'barber', 'service', 'branch'])
            ->where('validation_token', $token)
            ->firstOrFail();

        return view('admin.checkin.confirm', compact('queue'));
    }

    public function validate_checkin(Queue $queue): RedirectResponse
    {
        if (! $queue->isPending()) {
            $message = match ($queue->status) {
                'active'    => 'Antrean ini sudah divalidasi sebelumnya.',
                'called'    => 'Antrean ini sudah dipanggil oleh barber.',
                'completed' => 'Antrean ini sudah selesai.',
                'expired'   => 'Antrean ini sudah kedaluwarsa.',
                'skipped'   => 'Antrean ini sudah dilewati.',
                default     => 'Status antrean tidak valid untuk validasi.',
            };

            return redirect()->route('admin.checkin.confirm', $queue->validation_token)
                ->with('warning', $message);
        }

        $queue->update([
            'status'       => Queue::STATUS_ACTIVE,
            'checked_in_at' => now(),
        ]);

        // Beritahu customer: check-in berhasil (non-blokir bila push gagal)
        try {
            \Illuminate\Support\Facades\Bus::dispatchSync(new SendQueuePushNotification($queue->id, 'active'));
        } catch (\Throwable $e) {
            // Silent — notification failure should not block validation
        }

        return redirect()->route('admin.queues.manage')
            ->with('success', "Antrean #{$queue->queue_number} ({$queue->customer_name}) berhasil divalidasi!");
    }
}
