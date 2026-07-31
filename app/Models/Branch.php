<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'city',
        'description',
        'open_time',
        'close_time',
        'is_active',
        'queue_prefix',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class)->where('is_active', true);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function todayQueues()
    {
        return $this->queues()->whereDate('created_at', today());
    }

    /**
     * Generate next queue number for this branch.
     * Format: Q{prefix}{sequential} — e.g. Q0001 (Pusat), Q1001 (Selatan)
     * Sequence resets daily per branch.
     */
    public function getNextQueueNumber(): string
    {
        $prefix = 'Q' . ($this->queue_prefix ?? $this->id - 1);

        // Per-branch, per-day sequential counter — lock row to prevent race conditions
        $lastQueue = $this->queues()
            ->whereDate('created_at', today())
            ->where('queue_number', 'LIKE', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(queue_number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        $next = $lastQueue
            ? ((int) substr($lastQueue->queue_number, strlen($prefix))) + 1
            : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
