<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $branches = $this->liveBranches();

        $totalServingToday = Queue::where('status', Queue::STATUS_COMPLETED)
            ->whereDate('created_at', today())
            ->count();

        return view('welcome', compact('branches', 'totalServingToday'));
    }

    public function live(): JsonResponse
    {
        $branches = $this->liveBranches()->map(fn($b) => [
            'id' => $b->id,
            'waiting' => $b->live_waiting,
            'serving' => $b->live_serving,
            'completed' => $b->live_completed,
            'fastest_wait' => $b->live_fastest_wait,
            'is_open' => $b->live_is_open,
        ]);

        $totalServingToday = Queue::where('status', Queue::STATUS_COMPLETED)
            ->whereDate('created_at', today())
            ->count();

        return response()->json([
            'branches' => $branches,
            'total_served' => $totalServingToday,
        ]);
    }

    private function liveBranches()
    {
        $today = today();

        return Branch::where('is_active', true)
            ->with(['barbers' => fn($q) => $q->where('is_available', true)])
            ->get()
            ->map(function ($branch) use ($today) {
                $waiting = Queue::where('branch_id', $branch->id)
                    ->whereIn('status', [Queue::STATUS_PENDING, Queue::STATUS_ACTIVE])
                    ->whereDate('created_at', $today)
                    ->count();

                $serving = Queue::where('branch_id', $branch->id)
                    ->where('status', Queue::STATUS_CALLED)
                    ->whereDate('created_at', $today)
                    ->orderBy('id')
                    ->value('queue_number');

                $completed = Queue::where('branch_id', $branch->id)
                    ->where('status', Queue::STATUS_COMPLETED)
                    ->whereDate('created_at', $today)
                    ->count();

                // Estimasi tunggu tercepat: barber dengan antrean tersedikit
                $fastestWait = null;
                foreach ($branch->barbers as $barber) {
                    $wait = $barber->getQueueStats()['estimated_wait_minutes'];
                    if ($fastestWait === null || $wait < $fastestWait) {
                        $fastestWait = $wait;
                    }
                }

                $branch->live_waiting = $waiting;
                $branch->live_serving = $serving;
                $branch->live_completed = $completed;
                $branch->live_fastest_wait = $fastestWait;
                $branch->live_is_open = $this->isOpenNow($branch);

                return $branch;
            });
    }

    private function isOpenNow(Branch $branch): bool
    {
        if (!$branch->open_time || !$branch->close_time) {
            return true;
        }

        $now = now()->format('H:i');

        return $branch->open_time <= $branch->close_time
            ? ($now >= $branch->open_time && $now <= $branch->close_time)
            : ($now >= $branch->open_time || $now <= $branch->close_time);
    }
}
