<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Courier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderConfirmationMail;

class CheckoutController extends Controller
{
    private function getCart()
    {
        $sessionId = session()->getId();

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['session_id' => null]
            );
        } else {
            $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId],
                ['user_id' => null]
            );
        }

        return $cart;
    }

    public function index()
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('shop.checkout', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required_if:delivery_type,delivery|nullable|string|max:500',
            'delivery_notes' => 'nullable|string|max:1000',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_type' => 'required|in:online,cash',
        ]);

        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        foreach ($items as $item) {
            $product = $item->product;

            if (!$product->is_active) {
                return back()->with('error', 'Товар "' . $product->name . '" больше не доступен.');
            }

            if ($product->stock < $item->quantity) {
                return back()->with('error', 'Товара "' . $product->name . '" осталось только ' . $product->stock . ' шт.');
            }
        }

        DB::beginTransaction();

        try {
            $total = $items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
                'customer_name' => $request->customer_name,
                'customer_email' => Auth::user()->email,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => $request->delivery_type === 'delivery' ? $request->delivery_address : null,
                'delivery_notes' => $request->delivery_notes,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
            ]);

            foreach ($items as $item) {
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->quantity * $item->price,
                ]);

                $product->stock -= $item->quantity;
                $product->save();
            }

            $cart->items()->delete();

            // ============================================================
            // АВТОМАТИЧЕСКОЕ НАЗНАЧЕНИЕ КУРЬЕРА (ДО COMMIT, ЧТОБЫ СОХРАНИЛОСЬ!)
            // ============================================================
            if ($order->delivery_type === 'delivery') {
                $assigned = $this->assignCourierAutomatically($order);
                if ($assigned) {
                    Log::info('Курьер назначен: ' . $assigned->name . ' (заказ #' . $order->order_number . ')');
                } else {
                    Log::warning('Нет свободных курьеров для заказа #' . $order->order_number);
                }
            }

            DB::commit();

            // ============================================================
            // ОТПРАВКА EMAIL
            // ============================================================
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('Не удалось отправить письмо: ' . $e->getMessage());
            }

            // ============================================================
            // РЕДИРЕКТ В ЗАВИСИМОСТИ ОТ ТИПА ОПЛАТЫ
            // ============================================================
            if ($request->payment_type === 'online') {
                return redirect()->route('payment.create', $order);
            }

            if ($request->payment_type === 'cash') {
                return redirect()->route('payment.page', $order)->with('success', 'Заказ создан! Оплатите при получении.');
            }

            return redirect()->route('payment.page', $order)->with('success', 'Заказ успешно создан!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка оформления заказа: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при оформлении заказа. Попробуйте снова.');
        }
    }

    private function assignCourierAutomatically(Order $order): ?Courier
    {
        // Проверяем, что таблица couriers существует и есть активные курьеры
        try {
            $couriers = Courier::where('is_active', true)->get();
        } catch (\Exception $e) {
            Log::error('Ошибка получения курьеров: ' . $e->getMessage());
            return null;
        }

        if ($couriers->isEmpty()) {
            Log::warning('Нет активных курьеров в системе');
            return null;
        }

        $selectedCourier = $couriers->sortBy(function ($courier) {
            return $courier->activeOrdersCount();
        })->first();

        if (!$selectedCourier || !$selectedCourier->isAvailable()) {
            Log::warning('Курьер не доступен: ' . ($selectedCourier ? $selectedCourier->name : 'не найден'));
            return null;
        }

        // ПРЯМОЕ ОБНОВЛЕНИЕ ЧЕРЕЗ DB (минуя модель, чтобы точно сработало)
        DB::table('orders')
            ->where('id', $order->id)
            ->update([
                'courier_id' => $selectedCourier->id,
                'delivery_status' => 'assigned_to_courier',
                'courier_assigned_at' => now(),
            ]);

        Log::info('Курьер назначен через DB: ' . $selectedCourier->name . ' (заказ #' . $order->order_number . ')');

        return $selectedCourier;
    }
}
