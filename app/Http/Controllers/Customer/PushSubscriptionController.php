<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Save a new push subscription from the browser.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint'                  => 'required|url',
            'keys.p256dh'               => 'required|string',
            'keys.auth'                 => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'endpoint' => $request->endpoint,
            ],
            [
                'public_key'       => $request->input('keys.p256dh'),
                'auth_token'       => $request->input('keys.auth'),
                'content_encoding' => 'aes128gcm',
            ]
        );

        return response()->json(['status' => 'subscribed']);
    }

    /**
     * Remove a push subscription (user unsubscribes).
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|url',
        ]);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->endpoint)
            ->delete();

        return response()->json(['status' => 'unsubscribed']);
    }
}
