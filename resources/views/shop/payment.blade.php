@extends('layouts.shop')

@section('title', 'Заказ #' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6 text-center">
            ℹ️ {{ session('info') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-center">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Чек -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <!-- Шапка чека -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5 text-center text-white">
            <div class="flex justify-between items-center">
                <div class="text-left">
                    <p class="text-xs opacity-80">ЗАКАЗ №</p>
                    <p class="text-xl font-bold tracking-wider">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs opacity-80">СТАТУС</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        @if($order->status == 'completed') bg-green-500 text-white
                        @elseif($order->status == 'processing') bg-blue-500 text-white
                        @elseif($order->status == 'cancelled') bg-red-500 text-white
                        @else bg-yellow-500 text-white @endif">
                        @if($order->status == 'pending') Ожидает
                        @elseif($order->status == 'processing') В обработке
                        @elseif($order->status == 'completed') Выполнен
                        @elseif($order->status == 'cancelled') Отменен
                        @else {{ $order->status }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Тело чека -->
        <div class="p-6">
            <!-- Сумма -->
            <div class="text-center mb-6">
                <p class="text-gray-500 text-sm">Итого к оплате</p>
                <p class="text-4xl font-black text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</p>
                <p class="text-gray-400 text-xs mt-1">Оплата:
                    @if($order->payment_type == 'online')
                        <span class="text-green-600">✅ Оплачено онлайн</span>
                    @else
                        <span class="text-yellow-600">🔄 При получении</span>
                    @endif
                </p>
            </div>

            <!-- Состав заказа -->
            <div class="border-t border-gray-100 pt-4 mb-4">
                <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold mb-2">Состав заказа</p>
                @foreach($order->items as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                        <p class="text-gray-400 text-sm">{{ number_format($item->product_price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</p>
                    </div>
                    <p class="font-semibold text-gray-800">{{ number_format($item->total, 0, ',', ' ') }} ₽</p>
                </div>
                @endforeach
            </div>

            <!-- Данные получателя -->
            <div class="border-t border-gray-100 pt-4 mb-4">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Получатель</p>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Телефон</p>
                        <p class="font-medium">{{ $order->customer_phone }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-gray-400 text-xs uppercase tracking-wider font-semibold">Способ получения</p>
                    <p class="font-medium">
                        @if($order->delivery_type == 'delivery')
                            🚚 Доставка курьером
                        @else
                            🏠 Самовывоз из кафе
                        @endif
                    </p>
                    @if($order->delivery_type == 'delivery' && $order->delivery_address)
                        <p class="text-sm text-gray-500 mt-1">📍 {{ $order->delivery_address }}</p>
                    @endif
                </div>
            </div>

            <!-- Дата -->
            <div class="border-t border-gray-100 pt-4 text-center text-gray-400 text-sm">
                <p>Заказ оформлен: {{ $order->created_at->format('d.m.Y H:i') }}</p>
                <p class="text-xs mt-1">Спасибо, что выбрали GooDFooD! 🍔</p>
            </div>
        </div>

        <!-- Кнопки -->
        <div class="bg-gray-50 px-6 py-4 flex flex-wrap justify-center gap-3">
            <a href="{{ route('catalog') }}" class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition">
                🍽️ Продолжить покупки
            </a>
            <button onclick="window.print()" class="border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-6 py-2.5 rounded-xl text-sm transition">
                🖨️ Распечатать чек
            </button>
            @if($order->delivery_type == 'pickup' && $order->payment_type != 'online')
                <a href="#" class="border border-green-300 hover:bg-green-50 text-green-700 font-medium px-6 py-2.5 rounded-xl text-sm transition">
                    📍 Забрать в кафе
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
