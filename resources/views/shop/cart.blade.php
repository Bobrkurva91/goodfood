@extends('layouts.shop')

@section('title', 'Корзина')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">🛒 Корзина</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($items->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left py-4 px-6">Товар</th>
                        <th class="text-center py-4 px-6">Цена</th>
                        <th class="text-center py-4 px-6">Количество</th>
                        <th class="text-center py-4 px-6">Сумма</th>
                        <th class="text-center py-4 px-6"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center mr-4">
                                    @if($item->product->image)
                                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                    @else
                                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ Str::limit($item->product->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center py-4 px-6">
                            {{ number_format($item->price, 0, ',', ' ') }} ₽
                        </td>
                        <td class="text-center py-4 px-6">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="w-20 text-center border rounded px-2 py-1">
                                <button type="submit" class="text-blue-600 hover:text-blue-800 ml-2">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center py-4 px-6 font-semibold">
                            {{ number_format($item->quantity * $item->price, 0, ',', ' ') }} ₽
                        </td>
                        <td class="text-center py-4 px-6">
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
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
            </table>

            <div class="bg-gray-50 p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Итого:</span>
                    <span class="text-2xl font-bold text-red-600">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                </div>

                <div class="flex justify-between items-center">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-500 hover:text-red-600">
                            <i class="fas fa-trash-alt mr-1"></i> Очистить корзину
                        </button>
                    </form>

                    @if($items->count() > 0)
    <a href="{{ route('checkout') }}" class="bg-red-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
        Оформить заказ
    </a>
@else
    <button disabled class="bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed">
        Оформить заказ
    </button>
@endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-6">Ваша корзина пуста</p>
            <a href="{{ route('catalog') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                Перейти в каталог
            </a>
        </div>
    @endif
</div>
@endsection
