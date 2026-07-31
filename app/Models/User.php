<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBarber(): bool
    {
        return $this->role === 'barber';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // Relationships
    public function barber()
    {
        return $this->hasOne(Barber::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class, 'customer_id');
    }

    public function activeQueue(int $branchId)
    {
        return $this->queues()
            ->where('branch_id', $branchId)
            ->whereIn('status', ['pending', 'active', 'called'])
            ->first();
    }
}
