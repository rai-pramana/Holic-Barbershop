<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Branch;
use App\Models\Queue;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Run auto-expire and auto-skip on page load (simple approach without cron)
        Queue::expirePending();
        Queue::autoSkipCalled();

        $totalBranches  = Branch::where('is_active', true)->count();
        $totalBarbers   = Barber::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $todayQueues = Queue::whereDate('created_at', today())->get();
        $statusSummary = [
            'pending'   => $todayQueues->where('status', 'pending')->count(),
            'active'    => $todayQueues->where('status', 'active')->count(),
            'called'    => $todayQueues->where('status', 'called')->count(),
            'completed' => $todayQueues->where('status', 'completed')->count(),
            'skipped'   => $todayQueues->where('status', 'skipped')->count(),
            'expired'   => $todayQueues->where('status', 'expired')->count(),
        ];

        $recentQueues = Queue::with(['customer', 'barber', 'service', 'branch'])
            ->whereDate('created_at', today())
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalBranches',
            'totalBarbers',
            'totalCustomers',
            'statusSummary',
            'recentQueues'
        ));
    }
}
