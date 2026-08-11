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
    ];

    protected $casts = [
        'estimated_start' => 'datetime',
        'checked_in_at'   => 'datetime',
        'called_at'       => 'datetime',
        'completed_at'    => 'datetime',
        'expired_at'      => 'datetime',
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

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'yellow',
            'active'    => 'blue',
            'called'    => 'purple',
            'completed' => 'green',
            'skipped'   => 'red',
            'expired'   => 'gray',
            default     => 'gray',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'badge-warning',
            'active'    => 'badge-info',
            'called'    => 'badge-primary',
            'completed' => 'badge-success',
            'skipped'   => 'badge-danger',
            'expired'   => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }

    /**
     * Get QR code content URL (for admin to scan)
     */
    public function getQrCheckinUrlAttribute(): string
    {
        return route('admin.checkin.confirm', $this->validation_token);
    }

    /**
     * Calculate estimated waiting time in minutes
     */
    public function getEstimatedWaitMinutesAttribute(): int
    {
        $queuesAhead = Queue::where('branch_id', $this->branch_id)
            ->where('barber_id', $this->barber_id)
            ->whereIn('status', ['active', 'called'])
            ->where('id', '<', $this->id)
            ->whereDate('created_at', today())
            ->count();

        $pendingAhead = Queue::where('branch_id', $this->branch_id)
            ->where('barber_id', $this->barber_id)
            ->where('status', 'pending')
            ->where('id', '<', $this->id)
            ->whereDate('created_at', today())
            ->count();

        $duration = $this->service->duration_minutes ?? 30;
        return ($queuesAhead + $pendingAhead) * $duration;
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
