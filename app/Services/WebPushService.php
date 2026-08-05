<?php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'    => config('app.url'),
                'publicKey'  => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setDefaultOptions([
            'TTL'     => 3600,    // 1 hour
            'urgency' => 'high',
        ]);
    }

    /**
     * Send a push notification to all subscriptions of a user.
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = []): void
    {
        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'icon'  => '/icons/icon-192.png',
            'badge' => '/icons/badge-72.png',
            'data'  => $data,
            'tag'   => 'queue-notification',    // Replace older notifications of same type
            'renotify' => true,
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint'        => $sub->endpoint,
                'publicKey'       => $sub->public_key,
                'authToken'       => $sub->auth_token,
                'contentEncoding' => $sub->content_encoding ?? 'aesgcm',
            ]);

            $this->webPush->queueNotification($subscription, $payload);
        }

        // Send all queued notifications and handle results
        $expiredEndpoints = [];
        foreach ($this->webPush->flush() as $report) {
            if (! $report->isSuccess()) {
                $reason = $report->getReason();
                Log::warning('Push notification failed', [
                    'endpoint' => $report->getRequest()->getUri()->__toString(),
                    'reason'   => $reason,
                ]);

                // Remove expired/invalid subscriptions (410 = Gone, 404 = Not Found)
                if (in_array($report->getResponse()?->getStatusCode(), [404, 410])) {
                    $expiredEndpoints[] = $report->getRequest()->getUri()->__toString();
                }
            }
        }

        // Clean up expired subscriptions
        if (! empty($expiredEndpoints)) {
            PushSubscription::where('user_id', $userId)
                ->whereIn('endpoint', $expiredEndpoints)
                ->delete();
        }
    }
}
