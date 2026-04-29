<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Получить или создать корзину для текущего пользователя/сессии
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
    
    // Просмотр корзины
    public function index()
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();
        $total = $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
        
        return view('shop.cart', compact('items', 'total', 'cart'));
    }
    
    // Добавление товара в корзину
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'integer|min:1|max:99'
        ]);
        
        $quantity = $request->get('quantity', 1);
        $cart = $this->getCart();
        
        // Проверяем, есть ли уже такой товар в корзине
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();
        
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }
        
        return redirect()->back()->with('success', 'Товар добавлен в корзину');
    }
    
    // Обновление количества
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);
        
        $item->update(['quantity' => $request->quantity]);
        
        return redirect()->route('cart.index')->with('success', 'Корзина обновлена');
    }
    
    // Удаление товара из корзины
    public function remove(CartItem $item)
    {
        $item->delete();
        
        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины');
    }
    
    // Очистка корзины
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        
        return redirect()->route('cart.index')->with('success', 'Корзина очищена');
    }
    
    // Получение количества товаров в корзине (для AJAX)
    public function count()
    {
        $cart = $this->getCart();
        $count = $cart->items()->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}