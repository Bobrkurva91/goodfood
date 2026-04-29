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
}
