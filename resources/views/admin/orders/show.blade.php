@extends('layouts.shop')

@section('title', 'Заказ #' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Заказ #{{ $order->order_number }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Информация о заказе -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Состав заказа -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">🛒 Состав заказа</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b pb-3">
                        <div>
                            <p class="font-medium">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">{{ number_format($item->product_price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold">{{ number_format($item->total, 0, ',', ' ') }} ₽</p>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center mt-4 pt-3 border-t">
                    <span class="text-lg font-bold">Итого:</span>
                    <span class="text-2xl font-bold text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</span>
                </div>
            </div>
        </div>

        <!-- Информация о покупателе и доставке -->
        <div class="space-y-6">
            <!-- Статусы -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">📊 Статусы</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Статус заказа:</span>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100' }}">
                            @switch($order->status)
                                @case('pending') Ожидает @break
                                @case('processing') В обработке @break
                                @case('completed') Выполнен @break
                                @case('cancelled') Отменен @break
                                @default {{ $order->status }}
                            @endswitch
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Статус оплаты:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $order->payment_status === 'paid' ? 'Оплачен' : 'Не оплачен' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Покупатель -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">👤 Покупатель</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">Имя:</span> {{ $order->customer_name }}</p>
                    <p><span class="text-gray-500">Телефон:</span> {{ $order->customer_phone }}</p>
                    <p><span class="text-gray-500">Email:</span> {{ $order->customer_email }}</p>
                </div>
            </div>

            <!-- Доставка -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">🚚 Доставка</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">Адрес:</span> {{ $order->delivery_address }}</p>
                    @if($order->delivery_notes)
                        <p><span class="text-gray-500">Комментарий:</span> {{ $order->delivery_notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Действия -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <a href="{{ route('admin.orders.edit', $order) }}" class="block text-center bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-edit mr-2"></i> Редактировать статус
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
