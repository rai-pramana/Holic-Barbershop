<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Queue extends Model
{
    use HasFactory;

    const STATUS_PENDING   = 'pending';
    const STATUS_ACTIVE    = 'active';
    const STATUS_CALLED    = 'called';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SKIPPED   = 'skipped';
    const STATUS_EXPIRED   = 'expired';

    protected $fillable = [
        'queue_number',
        'customer_id',
        'barber_id',
        'service_id',
        'branch_id',
        'status',
        'notes',
        'guest_name',
        'guest_phone',
        'validation_token',
        'estimated_start',
        'checked_in_at',
        'called_at',
        'completed_at',
        'expired_at',
        'notified_near_at',
        'notified_near_level',
    ];

    protected $casts = [
        'estimated_start' => 'datetime',
        'checked_in_at'   => 'datetime',
        'called_at'       => 'datetime',
        'completed_at'    => 'datetime',
        'expired_at'      => 'datetime',
        'notified_near_at' => 'datetime',
    ];

    /**
     * Auto-generate unique validation token on creation
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Queue $queue) {
            $queue->validation_token = Str::random(32);
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Status Helpers
    public function isPending(): bool   { return $this->status === self::STATUS_PENDING; }
    public function isActive(): bool    { return $this->status === self::STATUS_ACTIVE; }
    public function isCalled(): bool    { return $this->status === self::STATUS_CALLED; }
    public function isCompleted(): bool { return $this->status === self::STATUS_COMPLETED; }
    public function isSkipped(): bool   { return $this->status === self::STATUS_SKIPPED; }
    public function isExpired(): bool   { return $this->status === self::STATUS_EXPIRED; }

    public function isActive_or_Pending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_ACTIVE, self::STATUS_CALLED]);
    }

    /** True if this is a walk-in queue (uses system guest user + has guest_name) */
    public function isGuest(): bool
    {
        return !is_null($this->guest_name);
    }

    /** Display name for any queue type (account or walk-in) */
    public function getCustomerNameAttribute(): string
    {
        if ($this->isGuest()) {
            return $this->guest_name . ' (Walk-in)';
        }
        return $this->customer?->name ?? '—';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'active'    => 'Hadir / Tervalidasi',
            'called'    => 'Dipanggil',
            'completed' => 'Selesai',
            'skipped'   => 'Dilewati',
            'expired'   => 'Kedaluwarsa',
            default     => ucfirst($this->status),
        };
    }

    public function getPositionInQueueAttribute(): int
    {
        return Queue::where('branch_id', $this->branch_id)
            ->where('barber_id', $this->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->where('id', '<=', $this->id)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Jumlah antrean di depan antrean ini (barber + cabang sama, hari ini).
     */
    public function getAheadCountAttribute(): int
    {
        return Queue::where('branch_id', $this->branch_id)
            ->where('barber_id', $this->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->where('id', '<', $this->id)
            ->whereDate('created_at', today())
            ->count();
    }

    /**
     * Cari antrean yang turun ke level "dekat" berikutnya (3 → 2 → 1 di depan)
     * dan belum pernah diberitahu untuk level tersebut. Dipanggil setiap ada
     * perubahan antrean di barber yang sama (panggil/selesai/lepas).
     * Kembalikan pasangan [queue, level].
     */
    public static function newlyNear(Queue $changed, int $threshold = 3)
    {
        return Queue::where('branch_id', $changed->branch_id)
            ->where('barber_id', $changed->barber_id)
            ->whereIn('status', ['active', 'called', 'pending'])
            ->where('id', '>', $changed->id)
            ->whereDate('created_at', today())
            ->with('service')
            ->get()
            ->map(fn(Queue $q) => [$q, $q->ahead_count])
            ->filter(fn($pair) => $pair[1] >= 1
                && $pair[1] <= $threshold
                && ($pair[0]->notified_near_level === null || $pair[0]->notified_near_level > $pair[1]));
    }

    /**
     * Auto-expire pending queues older than 60 minutes
     */
    public static function expirePending(): int
    {
        return self::where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->update(['status' => 'expired']);
    }

    /**
     * Auto-skip called queues older than 5 minutes
     */
    public static function autoSkipCalled(): int
    {
        return self::where('status', 'called')
            ->where('called_at', '<=', now()->subMinutes(5))
            ->update(['status' => 'skipped']);
    }
}
