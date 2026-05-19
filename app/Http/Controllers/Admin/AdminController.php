<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.index', compact('totalOrders', 'totalProducts', 'totalUsers', 'pendingOrders'));
    }

    public function reviews()
{
    $reviews = \App\Models\Review::with(['user', 'product'])->orderBy('id', 'desc')->paginate(20);
    return view('admin.reviews', compact('reviews'));
}

public function approveReview($id)
{
    $review = \App\Models\Review::findOrFail($id);
    $review->update(['status' => 'approved']);
    return back()->with('success', 'Отзыв одобрен');
}

public function rejectReview($id)
{
    $review = \App\Models\Review::findOrFail($id);
    $review->update(['status' => 'rejected']);
    return back()->with('success', 'Отзыв отклонен');
}
}
