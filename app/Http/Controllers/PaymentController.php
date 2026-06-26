<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use YooKassa\Client;

class PaymentController extends Controller
{
    /**
     * Страница оплаты (заглушка / чек)
     */
    public function page(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }

        return view('shop.payment', compact('order'));
    }

    /**
     * Создание платежа в ЮKassa
     */
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.page', $order)
                ->with('info', 'Заказ уже оплачен');
        }

        $client = new Client();
        $client->setAuth(
            config('services.yookassa.shop_id'),
            config('services.yookassa.secret_key')
        );

        try {
            $payment = $client->createPayment([
                'amount' => [
                    'value' => $order->total_amount,
                    'currency' => 'RUB'
                ],
                'payment_method_data' => [
                    'type' => 'bank_card'
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => route('payment.success', $order)
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ],
                'capture' => true,
                'description' => 'Оплата заказа #' . $order->order_number,
            ]);

            $order->update([
                'payment_id' => $payment->id
            ]);

            return redirect($payment->confirmation->confirmation_url);

        } catch (\Exception $e) {
            return redirect()->route('payment.page', $order)
                ->with('error', 'Ошибка при создании платежа: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        return redirect()->route('payment.page', $order)
            ->with('success', 'Оплата прошла успешно! Спасибо за заказ.');
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        if (isset($data['event']) && $data['event'] === 'payment.succeeded') {
            $paymentId = $data['object']['id'];
            $order = Order::where('payment_id', $paymentId)->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function cancel(Order $order)
    {
        return redirect()->route('cart.index')
            ->with('error', 'Оплата была отменена. Попробуйте снова.');
    }
}
