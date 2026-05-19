<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Показать все товары в избранном
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlists()->with('product')->get();
        return view('shop.wishlist', compact('wishlistItems'));
    }

    // Добавить товар в избранное
    public function add(Product $product)
    {
        $user = Auth::user();

        // Проверяем, нет ли уже этого товара в избранном
        $exists = $user->wishlists()->where('product_id', $product->id)->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return back()->with('success', 'Товар добавлен в избранное');
        }

        return back()->with('info', 'Товар уже в избранном');
    }

    // Удалить товар из избранного
    public function remove(Product $product)
    {
        $user = Auth::user();
        $user->wishlists()->where('product_id', $product->id)->delete();

        return back()->with('success', 'Товар удален из избранного');
    }

    // Получить количество товаров в избранном (для AJAX)
    public function count()
    {
        $user = Auth::user();
        $count = $user->wishlistCount();
        return response()->json(['count' => $count]);
    }
}
