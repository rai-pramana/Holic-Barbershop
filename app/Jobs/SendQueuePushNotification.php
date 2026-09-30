<?php

namespace App\Jobs;

use App\Models\Queue;
use App\Services\WebPushService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendQueuePushNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        private readonly int    $queueId,
        private readonly string $event,  // 'created' | 'called' | 'active' | 'completed' | 'skipped' | 'near'
    ) {}

    public function handle(WebPushService $pushService): void
    {
        $queue = Queue::with(['customer', 'barber', 'branch', 'service'])->find($this->queueId);

        if (! $queue) {
            Log::warning('Push notification: queue not found', ['queue_id' => $this->queueId]);
            return;
        }

        // ── Web Push title & body ──────────────────────────────────────────
        $barberName = $queue->barber?->name ?? 'barber kami';
        $branchName = $queue->branch?->name ?? 'HOLIC Barbershop';
        [$title, $body] = match($this->event) {
            'created' => [
                'Antrean Baru Masuk',
                "Antrean {$queue->queue_number} ({$queue->customer_name}) di {$branchName}.",
            ],
            'called' => [
                'Nomor Anda Dipanggil!',
                "Antrean {$queue->queue_number} — Segera ke kursi {$barberName}.",
            ],
            'active' => [
                'Check-in Berhasil!',
                "Antrean {$queue->queue_number} di {$branchName} aktif. Silakan tunggu dipanggil.",
            ],
            'completed' => [
                'Layanan Selesai',
                "Terima kasih telah mengunjungi {$branchName}! Sampai jumpa lagi.",
            ],
            'skipped' => [
                'Antrean Dilewati',
                "Antrean {$queue->queue_number} Anda dilewati. Silakan hubungi petugas.",
            ],
            'near' => [
                'Sebentar Lagi Giliran Anda!',
                "Tinggal {$queue->ahead_count} antrean lagi sebelum {$queue->queue_number}. Bersiap ke kursi {$barberName}.",
            ],
            default => ['HOLIC Barbershop', "Status antrean Anda berubah: {$queue->status_label}"],
        };

        // ── Send Web Push (online customers only) ─────────────────────────
        if ($queue->customer_id) {
            try {
                $pushService->sendToUser(
                    userId: $queue->customer_id,
                    title:  $title,
                    body:   $body,
                    data:   [
                        'url'          => route('customer.queue.status', $queue->id),
                        'queue_number' => $queue->queue_number,
                        'event'        => $this->event,
                    ],
                );
            } catch (\Throwable $e) {
                Log::warning('Web Push failed (non-fatal)', ['error' => $e->getMessage()]);
            }
        }

        // ── Antrean baru → beritahu semua admin (agar popup subscribe ada gunanya)
        if ($this->event === 'created') {
            $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                try {
                    $pushService->sendToUser(
                        userId: $adminId,
                        title:  $title,
                        body:   $body,
                        data:   [
                            'url'          => route('admin.queues.manage', ['branch_id' => $queue->branch_id]),
                            'queue_number' => $queue->queue_number,
                            'event'        => $this->event,
                        ],
                    );
                } catch (\Throwable $e) {
                    Log::warning('Web Push admin failed (non-fatal)', ['error' => $e->getMessage()]);
                }
            }
        }

    }

    public function failed(\Throwable $e): void
    {
        Log::error('Push notification job failed', [
            'queue_id' => $this->queueId,
            'event'    => $this->event,
            'error'    => $e->getMessage(),
        ]);
    }
}
