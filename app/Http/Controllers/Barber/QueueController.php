<?php

namespace App\Http\Controllers\Barber;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    private function getBarber()
    {
        return Auth::user()->barber;
    }

    /**
     * Call the next active queue (Active → Called)
     */
    public function call(Queue $queue): RedirectResponse
    {
        $barber = $this->getBarber();

        if ($queue->barber_id !== $barber->id) {
            abort(403, 'Bukan antrean Anda.');
        }

        if ($queue->status !== Queue::STATUS_ACTIVE) {
            return back()->with('error', 'Hanya antrean yang sudah check-in yang dapat dipanggil.');
        }

        $queue->update([
            'status'    => Queue::STATUS_CALLED,
            'called_at' => now(),
        ]);

        return back()->with('success', "Antrean #{$queue->queue_number} berhasil dipanggil.");
    }

    /**
     * Mark queue as completed (Called → Completed)
     */
    public function complete(Queue $queue): RedirectResponse
    {
        $barber = $this->getBarber();

        if ($queue->barber_id !== $barber->id) {
            abort(403, 'Bukan antrean Anda.');
        }

        if ($queue->status !== Queue::STATUS_CALLED) {
            return back()->with('error', 'Antrean harus dalam status dipanggil untuk diselesaikan.');
        }

        $queue->update([
            'status'       => Queue::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return back()->with('success', "Antrean #{$queue->queue_number} telah selesai.");
    }

    /**
     * Skip the queue (Called → Skipped) — customer not present
     */
    public function skip(Queue $queue): RedirectResponse
    {
        $barber = $this->getBarber();

        if ($queue->barber_id !== $barber->id) {
            abort(403, 'Bukan antrean Anda.');
        }

        if (!in_array($queue->status, [Queue::STATUS_CALLED, Queue::STATUS_ACTIVE])) {
            return back()->with('error', 'Hanya antrean aktif atau dipanggil yang dapat di-skip.');
        }

        $queue->update(['status' => Queue::STATUS_SKIPPED]);

        return back()->with('success', "Antrean #{$queue->queue_number} telah dilewati.");
    }
}
