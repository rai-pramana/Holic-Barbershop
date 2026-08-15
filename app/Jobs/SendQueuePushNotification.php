<?php

namespace App\Jobs;

use App\Models\Queue;
use App\Services\WebPushService;
use App\Services\WhatsAppService;
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
        private readonly string $event,  // 'called' | 'active' | 'completed' | 'skipped'
    ) {}

    public function handle(WebPushService $pushService, WhatsAppService $waService): void
    {
        $queue = Queue::with(['customer', 'barber', 'branch', 'service'])->find($this->queueId);

        if (! $queue) {
            Log::warning('Push notification: queue not found', ['queue_id' => $this->queueId]);
            return;
        }

        // ── Web Push title & body ──────────────────────────────────────────
        [$title, $body] = match($this->event) {
            'called' => [
                'Nomor Anda Dipanggil!',
                "Antrean {$queue->queue_number} — Segera ke kursi barber {$queue->barber->name}.",
            ],
            'active' => [
                'Check-in Berhasil!',
                "Antrean {$queue->queue_number} di {$queue->branch->name} aktif. Silakan tunggu dipanggil.",
            ],
            'completed' => [
                'Layanan Selesai',
                "Terima kasih telah mengunjungi {$queue->branch->name}! Sampai jumpa lagi.",
            ],
            'skipped' => [
                'Antrean Dilewati',
                "Antrean {$queue->queue_number} Anda dilewati. Silakan hubungi petugas.",
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

        // ── Send WhatsApp via Fonnte ───────────────────────────────────────
        // Resolve phone: online customer phone, or walk-in guest_phone
        $phone = $queue->customer?->phone ?? $queue->guest_phone ?? null;

        if ($phone) {
            $waMessage = $this->buildWhatsAppMessage($queue, $this->event);
            $waService->send($phone, $waMessage);
        }
    }

    /**
     * Build a friendly, human-readable WhatsApp message per event.
     */
    private function buildWhatsAppMessage(Queue $queue, string $event): string
    {
        $name   = $queue->customer_name;
        $number = $queue->queue_number;
        $barber = $queue->barber?->name ?? 'barber';
        $branch = $queue->branch?->name ?? 'HOLIC Barbershop';

        return match($event) {
            'active' =>
                "Halo, {$name}!\n\n" .
                "Check-in berhasil untuk antrean *{$number}* di *{$branch}*.\n" .
                "Barber Anda: *{$barber}*\n\n" .
                "Silakan tunggu — kami akan memberitahu Anda saat giliran tiba.\n\n" .
                "_HOLIC Barbershop_",

            'called' =>
                "Halo, {$name}!\n\n" .
                "*ANTREAN {$number} DIPANGGIL!*\n\n" .
                "Segera menuju kursi barber *{$barber}*.\n" .
                "Jika tidak hadir dalam 5 menit, antrean akan dilewati.\n\n" .
                "_HOLIC Barbershop_",

            'completed' =>
                "Halo, {$name}!\n\n" .
                "Layanan antrean *{$number}* telah selesai.\n" .
                "Terima kasih sudah mengunjungi *{$branch}*!\n\n" .
                "Sampai jumpa lagi dan selamat menikmati tampilan baru Anda!\n\n" .
                "_HOLIC Barbershop_",

            'skipped' =>
                "Halo, {$name}!\n\n" .
                "Antrean *{$number}* Anda telah dilewati karena tidak hadir saat dipanggil.\n\n" .
                "Silakan hubungi petugas di loket *{$branch}* untuk informasi lebih lanjut.\n\n" .
                "_HOLIC Barbershop_",

            default =>
                "Halo, {$name}!\n\n" .
                "Status antrean *{$number}* Anda di *{$branch}* telah berubah.\n\n" .
                "_HOLIC Barbershop_",
        };
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
