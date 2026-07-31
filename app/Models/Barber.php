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
}
