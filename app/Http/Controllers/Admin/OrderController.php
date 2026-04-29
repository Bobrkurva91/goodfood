<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Список заказов
     */
    public function index()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Детали заказа
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Форма редактирования статуса заказа
     */
    public function edit(Order $order)
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        return view('admin.orders.edit', compact('order', 'products'));
    }

    /**
     * Обновление статуса заказа
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Статус заказа #' . $order->order_number . ' обновлен');
    }

    /**
     * Добавление товара в заказ
     */
    public function addItem(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Проверяем, есть ли уже такой товар в заказе
        $existingItem = $order->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->total = $existingItem->quantity * $existingItem->product_price;
            $existingItem->save();
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $request->quantity,
                'total' => $product->price * $request->quantity,
            ]);
        }

        // Обновляем общую сумму заказа
        $order->total_amount = $order->items()->sum('total');
        $order->save();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Товар "' . $product->name . '" добавлен в заказ');
    }

    /**
     * Удаление товара из заказа
     */
    public function removeItem(Order $order, OrderItem $item)
    {
        // Проверяем, что позиция принадлежит заказу
        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $productName = $item->product_name;
        $item->delete();

        // Обновляем общую сумму заказа
        $order->total_amount = $order->items()->sum('total');
        $order->save();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Товар "' . $productName . '" удален из заказа');
    }

    /**
     * Удаление заказа
     */
    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ #' . $orderNumber . ' удален');
    }
}
