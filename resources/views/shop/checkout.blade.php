@extends('layouts.shop')

@section('title', 'Оформление заказа')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📝 Оформление заказа</h1>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Форма оформления заказа -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Контактные данные</h2>

                <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Ваше имя *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', Auth::user()->name) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        @error('customer_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Телефон *</label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                               placeholder="+7 (XXX) XXX-XX-XX"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        @error('customer_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                               class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2">
                        <p class="text-gray-500 text-sm mt-1">Письмо с подтверждением придет на этот адрес</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Адрес доставки *</label>
                        <textarea name="delivery_address" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                  required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Комментарий к заказу</label>
                        <textarea name="delivery_notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('delivery_notes') }}</textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Информация о заказе -->
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
        <h2 class="text-xl font-bold mb-4">🛒 Ваш заказ</h2>

        <div class="border-b pb-3 mb-3 max-h-96 overflow-y-auto">
            @foreach($items as $item)
            <div class="flex justify-between mb-3 pb-3 border-b border-gray-100">
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-gray-500 text-sm">
                        {{ number_format($item->price, 0, ',', ' ') }} ₽ × {{ $item->quantity }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="font-semibold text-gray-800">{{ number_format($item->quantity * $item->price, 0, ',', ' ') }} ₽</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-between mb-4 pt-2">
            <span class="font-bold text-lg">Итого:</span>
            <span class="font-bold text-2xl text-red-600">{{ number_format($total, 0, ',', ' ') }} ₽</span>
        </div>

        <button type="submit" form="checkout-form" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            Подтвердить заказ
        </button>

        <p class="text-gray-500 text-sm text-center mt-4">
            Нажимая "Подтвердить заказ", вы соглашаетесь с условиями доставки
        </p>
    </div>
</div>
@endsection
