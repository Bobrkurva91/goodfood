@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <!-- Изображение товара -->
            <div class="md:w-1/2">
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    @endif
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="md:w-1/2 p-8">
                <div class="mb-4">
                    <a href="{{ route('catalog', ['category' => $product->category_id]) }}" class="text-red-600 text-sm hover:underline">
                        {{ $product->category->name }}
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

                @if($product->is_on_sale)
                    <div class="mb-4">
                        <span class="bg-red-600 text-white text-sm font-bold px-3 py-1 rounded">Акция</span>
                    </div>
                @endif

                <p class="text-gray-600 mb-6">{{ $product->description }}</p>

                <div class="mb-6">
                    <span class="text-3xl font-bold text-red-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                </div>

                <div class="mb-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600">Наличие:</span>
                        @if($product->stock > 0)
                            <span class="text-green-600 font-semibold">В наличии ({{ $product->stock }} шт.)</span>
                        @else
                            <span class="text-red-600 font-semibold">Нет в наличии</span>
                        @endif
                    </div>
                </div>

                @auth
    <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-4">
        @csrf
        <div class="flex items-center space-x-4 mb-4">
    <label class="text-gray-600">Количество:</label>
    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
           class="w-20 border rounded-lg px-3 py-2 text-center">
    <span class="text-sm text-gray-500">доступно: {{ $product->stock }} шт.</span>
</div>
        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">
            <i class="fas fa-shopping-cart mr-2"></i> Добавить в корзину
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="block text-center w-full bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
        Войдите, чтобы купить
    </a>
@endauth
            </div>
        </div>
    </div>

    <!-- Похожие товары -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Похожие товары</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                <div class="h-40 bg-gray-200 flex items-center justify-center">
                    @if($related->image)
                        <img src="{{ $related->image }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-3xl text-gray-400"></i>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-1">{{ $related->name }}</h3>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-lg font-bold text-red-600">{{ number_format($related->price, 0, ',', ' ') }} ₽</span>
                        <a href="{{ route('product.show', $related->slug) }}" class="text-red-600 text-sm hover:underline">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
