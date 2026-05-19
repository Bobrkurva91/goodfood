<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== ОТНОШЕНИЯ (RELATIONS) ====================

    /**
     * Связь с заказами (один пользователь → много заказов)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Связь с избранным (один пользователь → много товаров в избранном)
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Связь с отзывами (один пользователь → много отзывов)
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ==================== ПРОВЕРКИ (CHECKS) ====================

    /**
     * Проверка, является ли пользователь администратором
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Проверка, находится ли товар в избранном у пользователя
     */
    public function isInWishlist(int $productId): bool
    {
        return $this->wishlists()->where('product_id', $productId)->exists();
    }

    /**
     * Проверка, покупал ли пользователь данный товар (только выполненные заказы)
     */
    public function hasPurchasedProduct(int $productId): bool
    {
        return $this->orders()
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'completed')
            ->exists();
    }

    // ==================== СЧЕТЧИКИ (COUNTERS) ====================

    /**
     * Получить количество товаров в избранном
     */
    public function wishlistCount(): int
    {
        return $this->wishlists()->count();
    }
}
