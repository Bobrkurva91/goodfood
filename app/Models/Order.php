<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo as CourierBelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'delivery_notes',
        'delivery_type',
        'payment_type',
        'payment_id',
        'courier_id',
        'delivery_status',
        'courier_assigned_at',
        'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'courier_assigned_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public static function generateOrderNumber()
    {
        return 'ORD' . date('Ymd') . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public static function assignCourierAutomatically(Order $order): ?Courier
    {
        $couriers = Courier::where('is_active', true)->get();

        if ($couriers->isEmpty()) {
            return null;
        }

        $selectedCourier = $couriers->sortBy(function ($courier) {
            return $courier->activeOrdersCount();
        })->first();

        if (!$selectedCourier || !$selectedCourier->isAvailable()) {
            return null;
        }

        $order->update([
            'courier_id' => $selectedCourier->id,
            'delivery_status' => 'assigned_to_courier',
            'courier_assigned_at' => now(),
        ]);

        return $selectedCourier;
    }
}
