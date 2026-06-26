<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Courier extends Authenticatable
{
    use Notifiable;

    protected $table = 'couriers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'vehicle',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrdersCount(): int
    {
        return $this->orders()
            ->whereIn('delivery_status', ['assigned_to_courier', 'courier_took', 'on_the_way'])
            ->count();
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->activeOrdersCount() < 3;
    }
}
