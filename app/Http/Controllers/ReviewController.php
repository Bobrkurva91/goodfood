<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();

        // Проверяем, покупал ли пользователь этот товар
        if (!$user->hasPurchasedProduct($product->id)) {
            return back()->with('error', 'Вы можете оставить отзыв только после покупки товара.');
        }

        // Проверяем, не оставлял ли уже отзыв
        $existing = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Вы уже оставили отзыв на этот товар.');
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Спасибо за отзыв! Он будет опубликован после проверки модератором.');
    }
}
