<?php

namespace App\Http\Controllers\Barber;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        Queue::expirePending();
        Queue::autoSkipCalled();

        $barber = Auth::user()->barber;

        $todayQueues = Queue::with(['customer', 'service'])
            ->where('barber_id', $barber->id)
            ->whereDate('created_at', today())
            ->orderBy('id')
            ->get();

        $activeQueue    = $todayQueues->whereIn('status', ['active', 'called'])->first();
        $pendingQueues  = $todayQueues->where('status', 'pending');
        $completedToday = $todayQueues->where('status', 'completed')->count();
        $skippedToday   = $todayQueues->where('status', 'skipped')->count();

        return view('barber.dashboard', compact(
            'barber',
            'todayQueues',
            'activeQueue',
            'pendingQueues',
            'completedToday',
            'skippedToday'
        ));
    }
}
