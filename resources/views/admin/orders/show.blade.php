@extends('layouts.shop')

@section('title', 'Заказ #' . $order->order_number)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Заказ #{{ $order->order_number }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ЛЕВАЯ КОЛОНКА: Состав заказа --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">🛒 Состав заказа</h2>
                @foreach($order->items as $item)
                <div class="flex justify-between border-b py-2">
                    <div>
                        <p class="font-medium">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($item->product_price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</p>
                    </div>
                    <p class="font-semibold">{{ number_format($item->total, 0, ',', ' ') }} ₽</p>
                </div>
                @endforeach
                <div class="flex justify-between mt-4 pt-2 border-t">
                    <span class="font-bold text-lg">Итого:</span>
                    <span class="font-bold text-2xl text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</span>
                </div>
            </div>
        </div>

        {{-- ПРАВАЯ КОЛОНКА: Информация о заказе и доставке --}}
        <div class="space-y-6">

            {{-- Блок: Статусы --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">📊 Статусы</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Статус заказа:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Оплата:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold
                            @if($order->payment_status == 'paid') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $order->payment_status == 'paid' ? '✅ Оплачен' : 'Не оплачен' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Доставка:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold
                            @if($order->delivery_status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->delivery_status == 'on_the_way') bg-blue-100 text-blue-800
                            @elseif($order->delivery_status == 'assigned_to_courier') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ str_replace('_', ' ', ucfirst($order->delivery_status ?? 'pending')) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Блок: Курьер --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">🚚 Курьер</h2>

                @if($order->courier)
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-xl">
                            {{ substr($order->courier->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $order->courier->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->courier->vehicle }}</p>
                            <p class="text-sm text-gray-500">{{ $order->courier->phone }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.orders.assignCourier', $order) }}">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <select name="courier_id" class="border rounded-lg px-3 py-2 text-sm w-full">
                                @foreach(\App\Models\Courier::where('is_active', true)->get() as $courier)
                                    <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                        {{ $courier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                🔄 Назначить
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-500 text-sm mb-4">Курьер не назначен</p>
                    <form method="POST" action="{{ route('admin.orders.assignCourier', $order) }}">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <select name="courier_id" class="border rounded-lg px-3 py-2 text-sm w-full" required>
                                <option value="">-- Выберите курьера --</option>
                                @foreach(\App\Models\Courier::where('is_active', true)->get() as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->vehicle }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                ➕ Назначить
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Блок: Изменение статуса доставки --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">📦 Статус доставки</h2>
                <form method="POST" action="{{ route('admin.orders.updateDelivery', $order) }}">
                    @csrf
                    <div class="flex items-center space-x-2">
                        <select name="delivery_status" class="border rounded-lg px-3 py-2 text-sm w-full">
                            <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                            <option value="assigned_to_courier" {{ $order->delivery_status == 'assigned_to_courier' ? 'selected' : '' }}>Назначен курьер</option>
                            <option value="courier_took" {{ $order->delivery_status == 'courier_took' ? 'selected' : '' }}>Курьер взял заказ</option>
                            <option value="on_the_way" {{ $order->delivery_status == 'on_the_way' ? 'selected' : '' }}>В пути</option>
                            <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>Доставлен</option>
                        </select>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                            ✅ Обновить
                        </button>
                    </div>
                </form>
            </div>

            {{-- Блок: Данные получателя --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">👤 Данные получателя</h2>
                <div class="space-y-1 text-sm">
                    <p><span class="text-gray-500">Имя:</span> {{ $order->customer_name }}</p>
                    <p><span class="text-gray-500">Телефон:</span> {{ $order->customer_phone }}</p>
                    <p><span class="text-gray-500">Email:</span> {{ $order->customer_email }}</p>
                    <p><span class="text-gray-500">Адрес:</span> {{ $order->delivery_address ?? 'Самовывоз' }}</p>
                </div>
            </div>

            {{-- Блок: Информация о доставке --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-bold mb-3">📌 Информация о доставке</h2>
                <div class="space-y-1 text-sm">
                    <p><span class="text-gray-500">Способ:</span> {{ $order->delivery_type == 'delivery' ? '🚚 Доставка' : '🏠 Самовывоз' }}</p>
                    <p><span class="text-gray-500">Оплата:</span> {{ $order->payment_type == 'online' ? '💳 Онлайн' : '💰 При получении' }}</p>
                    @if($order->courier_assigned_at)
                        <p><span class="text-gray-500">Назначен:</span> {{ $order->courier_assigned_at->format('d.m.Y H:i') }}</p>
                    @endif
                    @if($order->delivered_at)
                        <p><span class="text-gray-500">Доставлен:</span> {{ $order->delivered_at->format('d.m.Y H:i') }}</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
