@extends('layouts.shop')

@section('title', 'Редактирование заказа #' . $order->order_number)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Редактирование заказа #{{ $order->order_number }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Форма изменения статуса -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Статусы заказа</h2>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Статус заказа</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Статус оплаты</label>
                    <select name="payment_status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Оплачен</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Ошибка</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                    Сохранить статусы
                </button>
            </form>
        </div>

        <!-- Добавление товара -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Добавить товар</h2>
            <form method="POST" action="{{ route('admin.orders.addItem', $order) }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Товар</label>
                    <select name="product_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Выберите товар</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->price, 0, ',', ' ') }} ₽ (в наличии: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Количество</label>
                    <input type="number" name="quantity" value="1" min="1" max="99" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    + Добавить в заказ
                </button>
            </form>
        </div>
    </div>

    <!-- Текущие товары в заказе -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Товары в заказе</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Товар</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500">Кол-во</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Цена</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Сумма</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="px-4 py-3 text-sm text-gray-800">{{ $item->product_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 text-center">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 text-right">{{ number_format($item->product_price, 0, ',', ' ') }} ₽</td>
                        <td class="px-4 py-3 text-sm font-semibold text-green-600 text-right">{{ number_format($item->total, 0, ',', ' ') }} ₽</td>
                        <td class="px-4 py-3 text-center">
                            <form method="POST" action="{{ route('admin.orders.removeItem', [$order, $item]) }}" onsubmit="return confirm('Удалить товар из заказа?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-bold">Итого:</td>
                        <td class="px-4 py-3 text-right font-bold text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
