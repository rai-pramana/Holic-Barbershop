<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'duration_minutes',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_minutes >= 60) {
            $hours = floor($this->duration_minutes / 60);
            $minutes = $this->duration_minutes % 60;
            return $minutes > 0 ? "{$hours} jam {$minutes} menit" : "{$hours} jam";
        }
        return "{$this->duration_minutes} menit";
    }
}
