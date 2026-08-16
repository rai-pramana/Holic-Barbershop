<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'branch_id',
        'specialty',
        'bio',
        'photo',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function activeQueues()
    {
        return $this->queues()->whereIn('status', ['active', 'called']);
    }

    public function todayQueues()
    {
        return $this->queues()->whereDate('created_at', today());
    }

    /**
     * Get count of pending/active queues to determine "fastest barber"
     */
    public function getPendingQueueCount(): int
    {
        return $this->queues()
            ->whereIn('status', ['active', 'called', 'pending'])
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Get rich queue stats for the take-queue barber selection page.
     * Returns:
     *   pending_count        — total queues still waiting
     *   current_serving      — queue_number of the called queue (or null)
     *   estimated_wait_minutes — sum of remaining durations for each ahead queue
     */
    public function getQueueStats(): array
    {
        $queues = $this->queues()
            ->whereIn('status', ['active', 'called', 'pending'])
            ->whereDate('created_at', today())
            ->with('service')
            ->orderByRaw("FIELD(status, 'called', 'active', 'pending')")
            ->orderBy('id')
            ->get();

        $currentServing = $queues->firstWhere('status', 'called')?->queue_number;
        $pendingCount   = $queues->count();

        // Estimate total wait: remaining time for active/called + full time for pending
        $waitMinutes = 0;
        foreach ($queues as $q) {
            $dur = $q->service?->duration_minutes ?? 30;
            if (in_array($q->status, ['called', 'active'])) {
                $startedAt   = $q->checked_in_at ?? $q->called_at ?? $q->created_at;
                $elapsed     = max(0, \Carbon\Carbon::parse($startedAt)->diffInMinutes(now()));
                $waitMinutes += max(0, $dur - $elapsed);
            } else {
                $waitMinutes += $dur;
            }
        }

        return [
            'pending_count'          => $pendingCount,
            'current_serving'        => $currentServing,
            'estimated_wait_minutes' => $waitMinutes,
        ];
    }
}
