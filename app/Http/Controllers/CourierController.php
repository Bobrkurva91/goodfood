<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function dashboard()
    {
        $courier = Auth::guard('courier')->user();
        $orders = Order::where('courier_id', $courier->id)
            ->orderBy('id', 'desc')
            ->get();

        $newOrders = $orders->where('delivery_status', 'assigned_to_courier')->count();
        $onWayOrders = $orders->where('delivery_status', 'on_the_way')->count();
        $deliveredToday = $orders->filter(function ($order) {
            return $order->delivery_status === 'delivered' &&
                   $order->delivered_at &&
                   $order->delivered_at->isToday();
        })->count();

        return view('courier.dashboard', compact('orders', 'newOrders', 'onWayOrders', 'deliveredToday'));
    }

    public function took(Order $order)
    {
        $this->authorizeCourier($order);
        $order->update(['delivery_status' => 'courier_took']);
        return back()->with('success', 'Заказ взят в доставку');
    }

    public function onWay(Order $order)
    {
        $this->authorizeCourier($order);
        $order->update(['delivery_status' => 'on_the_way']);
        return back()->with('success', 'Заказ в пути');
    }

    public function delivered(Order $order)
    {
        $this->authorizeCourier($order);
        $order->update([
            'delivery_status' => 'delivered',
            'status' => 'completed',
            'delivered_at' => now(),
        ]);
        return back()->with('success', 'Заказ доставлен!');
    }

    private function authorizeCourier(Order $order)
    {
        if ($order->courier_id !== Auth::guard('courier')->id()) {
            abort(403, 'Это не ваш заказ');
        }
    }
}
