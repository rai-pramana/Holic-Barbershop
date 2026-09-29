<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Models\Queue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Save or update a push subscription for the authenticated user.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'         => 'required|url',
            'public_key'       => 'required|string',
            'auth_token'       => 'required|string',
            'content_encoding' => 'nullable|string',
        ]);

        PushSubscription::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'endpoint' => $request->endpoint,
            ],
            [
                'public_key'       => $request->public_key,
                'auth_token'       => $request->auth_token,
                'content_encoding' => $request->content_encoding ?? 'aes128gcm',
            ]
        );

        return response()->json(['ok' => true]);
    }

    /**
     * Remove a push subscription.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate(['endpoint' => 'required|url']);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Kirim notifikasi tes ke user saat ini (untuk diagnosa HP).
     */
    public function test(): JsonResponse
    {
        try {
            app(\App\Services\WebPushService::class)->sendToUser(
                userId: Auth::id(),
                title: 'Tes Notifikasi HOLIC',
                body: 'Jika Anda melihat ini di HP, push berfungsi. Kunci layar lalu coba lagi.',
                data: ['url' => route('customer.dashboard')],
            );
        } catch (\Throwable $e) {
            return response()->json(['sent' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'sent' => true,
            'total_for_user' => PushSubscription::where('user_id', Auth::id())->count(),
        ]);
    }

    /**
     * Cek apakah endpoint terdaftar di server (untuk diagnosa HP).
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate(['endpoint' => 'required|string']);

        $exists = PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->exists();

        return response()->json([
            'registered' => $exists,
            'total_for_user' => PushSubscription::where('user_id', Auth::id())->count(),
        ]);
    }
}