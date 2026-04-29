<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderConfirmationMail;

class CheckoutController extends Controller
{
    /**
     * Получить корзину текущего пользователя/сессии
     */
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

    /**
     * Страница оформления заказа
     */
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

    /**
 * Сохранение заказа
 */
public function store(Request $request)
{
    // Валидация данных формы
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'delivery_address' => 'required|string|max:500',
        'delivery_notes' => 'nullable|string|max:1000',
    ]);

    $cart = $this->getCart();
    $items = $cart->items()->with('product')->get();

    if ($items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Корзина пуста');
    }

    // ПРОВЕРКА НАЛИЧИЯ ТОВАРОВ
    foreach ($items as $item) {
        $product = $item->product;

        // Проверяем, активен ли товар
        if (!$product->is_active) {
            return back()->with('error', 'Товар "' . $product->name . '" больше не доступен. Пожалуйста, удалите его из корзины.');
        }

        // Проверяем, достаточно ли товара на складе
        if ($product->stock < $item->quantity) {
            return back()->with('error', 'Товара "' . $product->name . '" осталось только ' . $product->stock . ' шт. Пожалуйста, уменьшите количество.');
        }

        // Проверяем, что количество не превышает 99
        if ($item->quantity > 99) {
            return back()->with('error', 'Невозможно заказать более 99 единиц товара "' . $product->name . '"');
        }
    }

    // Начинаем транзакцию
    DB::beginTransaction();

    try {
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Создаем заказ
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => Order::generateOrderNumber(),
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'customer_name' => $request->customer_name,
            'customer_email' => Auth::user()->email,
            'customer_phone' => $request->customer_phone,
            'delivery_address' => $request->delivery_address,
            'delivery_notes' => $request->delivery_notes,
        ]);

        // Создаем позиции заказа и списываем товары
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

            // Уменьшаем количество товара на складе
            $product->stock -= $item->quantity;
            $product->save();
        }

        // Очищаем корзину
        $cart->items()->delete();

        // Отправка email (пока в лог-файл)
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            // Логируем ошибку, но не прерываем оформление заказа
            Log::error('Не удалось отправить письмо: ' . $e->getMessage());
        }

        DB::commit();

        return redirect()->route('payment.page', $order)->with('success', 'Заказ успешно создан!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Произошла ошибка при оформлении заказа. Попробуйте снова.');
    }
}
}
