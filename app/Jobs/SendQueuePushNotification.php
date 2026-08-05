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
        private readonly string $event,  // 'called' | 'completed' | 'skipped'
    ) {}

    public function handle(WebPushService $pushService): void
    {
        $queue = Queue::with(['customer', 'barber', 'branch', 'service'])->find($this->queueId);

        if (! $queue) {
            Log::warning('Push notification: queue not found', ['queue_id' => $this->queueId]);
            return;
        }

        [$title, $body] = match($this->event) {
            'called' => [
                '🔔 Nomor Anda Dipanggil!',
                "Antrean {$queue->queue_number} — Segera ke kursi barber {$queue->barber->name}.",
            ],
            'active' => [
                '✅ Check-in Berhasil!',
                "Antrean {$queue->queue_number} di {$queue->branch->name} aktif. Silakan tunggu dipanggil.",
            ],
            'completed' => [
                '✅ Layanan Selesai',
                "Terima kasih telah mengunjungi {$queue->branch->name}! Sampai jumpa lagi.",
            ],
            'skipped' => [
                '⚠️ Antrean Dilewati',
                "Antrean {$queue->queue_number} Anda dilewati. Silakan hubungi petugas.",
            ],
            default => ['HOLIC Barbershop', "Status antrean Anda berubah: {$queue->status_label}"],
        };

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
