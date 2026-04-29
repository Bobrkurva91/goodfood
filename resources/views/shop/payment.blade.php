@extends('layouts.shop')

@section('title', 'Оплата заказа')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Успешное сообщение -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Чек об оплате -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Заголовок чека -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
            <div class="text-center text-white">
                <i class="fas fa-check-circle text-4xl mb-2"></i>
                <h1 class="text-2xl font-bold">Заказ успешно оформлен!</h1>
                <p class="text-green-100">Номер заказа: {{ $order->order_number }}</p>
            </div>
        </div>

        <!-- Тело чека -->
        <div class="p-6">
            <!-- Информация о заказе -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Детали заказа</h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <p class="text-gray-500">Дата заказа:</p>
                    <p class="text-gray-800">{{ $order->created_at->format('d.m.Y H:i') }}</p>

                    <p class="text-gray-500">Статус:</p>
                    <p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Ожидает подтверждения
                        </span>
                    </p>

                    <p class="text-gray-500">Способ оплаты:</p>
                    <p class="text-gray-800">При получении (наличные/карта)</p>
                </div>
            </div>

            <!-- Данные получателя -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Данные получателя</h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <p class="text-gray-500">Получатель:</p>
                    <p class="text-gray-800">{{ $order->customer_name }}</p>

                    <p class="text-gray-500">Телефон:</p>
                    <p class="text-gray-800">{{ $order->customer_phone }}</p>

                    <p class="text-gray-500">Email:</p>
                    <p class="text-gray-800">{{ $order->customer_email }}</p>

                    <p class="text-gray-500">Адрес доставки:</p>
                    <p class="text-gray-800">{{ $order->delivery_address }}</p>

                    @if($order->delivery_notes)
                        <p class="text-gray-500">Комментарий:</p>
                        <p class="text-gray-800">{{ $order->delivery_notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Состав заказа -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">🛒 Состав заказа</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500">{{ number_format($item->product_price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold text-gray-800 ml-4">{{ number_format($item->total, 0, ',', ' ') }} ₽</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Итого -->
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-bold text-gray-800">Итого к оплате:</span>
                <span class="text-2xl font-bold text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</span>
            </div>

            <!-- Информация о подтверждении -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Как будет происходить оплата?</p>
                        <p>Это демонстрационная версия магазина. Наш менеджер свяжется с вами в ближайшее время для подтверждения заказа.</p>
                        <p class="mt-1">Оплата производится при получении заказа.</p>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('catalog') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition text-center">
                    Продолжить покупки
                </a>
                <button onclick="window.print()" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-print mr-2"></i> Распечатать чек
                </button>
            </div>
        </div>
    </div>

    <!-- Номер заказа для отслеживания -->
    <div class="text-center mt-6 text-gray-500 text-sm">
        <p>Сохраните номер заказа: <strong class="text-gray-700">{{ $order->order_number }}</strong></p>
        <p>Вы можете отслеживать статус заказа в личном кабинете</p>
    </div>
</div>
@endsection
