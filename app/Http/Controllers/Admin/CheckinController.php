<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Halaman loket check-in: scanner QR + input manual
     */
    public function index()
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.checkin.index', compact('branches'));
    }

    /**
     * Cari antrean berdasarkan nomor antrean (input manual)
     */
    public function search(Request $request)
    {
        $request->validate([
            'queue_number' => 'required|string',
            'branch_id'    => 'nullable|exists:branches,id',
        ]);

        $query = Queue::with(['customer', 'barber', 'service', 'branch'])
            ->where('queue_number', strtoupper(trim($request->queue_number)))
            ->whereDate('created_at', today());

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $queue = $query->first();

        if (! $queue) {
            return back()->with('error', "Antrean '{$request->queue_number}' tidak ditemukan hari ini.");
        }

        return redirect()->route('admin.checkin.confirm', $queue->validation_token);
    }

    /**
     * Tampilkan halaman konfirmasi sebelum validasi (hasil scan QR atau search)
     */
    public function confirm(string $token)
    {
        $queue = Queue::with(['customer', 'barber', 'service', 'branch'])
            ->where('validation_token', $token)
            ->firstOrFail();

        return view('admin.checkin.confirm', compact('queue'));
    }

    /**
     * Proses validasi — ubah status pending → active
     */
    public function validate_checkin(Queue $queue)
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

        return redirect()->route('admin.checkin.index')
            ->with('success', "✅ Antrean #{$queue->queue_number} ({$queue->customer_name}) berhasil divalidasi!");
    }
}
