<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Страница оплаты (заглушка по ТЗ)
     * 
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function index(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }
        
        return view('shop.payment', compact('order'));
    }
}