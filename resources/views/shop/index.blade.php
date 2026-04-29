@extends('layouts.shop')

@section('title', 'Главная')

@section('content')
<!-- Герой-секция с неоновой вывеской -->
<section class="relative overflow-hidden bg-black">
    <div class="absolute inset-0 bg-gradient-to-r from-red-900/50 to-purple-900/50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 lg:py-32 text-center">
        <div class="animate-pulse-slow">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-4 neon-text">
                GooDFooD
            </h1>
        </div>
        <p class="text-lg sm:text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Вкусная еда с быстрой доставкой
        </p>
        @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="neon-button bg-transparent border-2 border-red-500 text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-red-500 hover:text-white transition-all duration-300">
                    Зарегистрироваться
                </a>
                <a href="{{ route('login') }}" class="bg-white/10 backdrop-blur-sm text-white px-6 sm:px-8 py-3 rounded-lg font-semibold hover:bg-white/20 transition-all duration-300">
                    Войти
                </a>
            </div>
        @else
            <a href="{{ route('catalog') }}" class="neon-button inline-block bg-transparent border-2 border-red-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-500 hover:text-white transition-all duration-300">
                Перейти в каталог
            </a>
        @endguest
    </div>
</section>

<!-- Преимущества -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-truck text-2xl sm:text-3xl text-red-600"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Быстрая доставка</h3>
            <p class="text-sm sm:text-base text-gray-600">Доставка за 1-2 часа по городу</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-leaf text-2xl sm:text-3xl text-green-600"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Свежие продукты</h3>
            <p class="text-sm sm:text-base text-gray-600">Только качественные ингредиенты</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center card-hover">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tag text-2xl sm:text-3xl text-yellow-600"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Акции и скидки</h3>
            <p class="text-sm sm:text-base text-gray-600">Регулярные скидки для постоянных клиентов</p>
        </div>
    </div>
</section>

<!-- Новинки -->
@if($newProducts->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">🔥 Новинки</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($newProducts as $product)
        <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group">
            <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                @if($product->image && file_exists(public_path($product->image)))
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-utensils text-4xl text-gray-400 mb-2"></i>
                        <span class="text-xs text-gray-500">{{ $product->name }}</span>
                    </div>
                @endif
                <span class="absolute top-2 right-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">
                    Новинка
                </span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>
                <p class="text-gray-500 text-sm mb-2">{{ Str::limit($product->description, 50) }}</p>
                <div class="flex justify-between items-center mt-3">
                    <span class="text-xl font-bold text-red-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                    <a href="{{ route('product.show', $product->slug) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                        Подробнее
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Акционные товары -->
@if($saleProducts->count() > 0)
<section class="bg-gray-100 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">🎯 Акционные товары</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($saleProducts as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group">
                <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                    @if($product->image && file_exists(public_path($product->image)))
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tag text-4xl text-gray-400 mb-2"></i>
                            <span class="text-xs text-gray-500">{{ $product->name }}</span>
                        </div>
                    @endif
                    <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                        Акция -20%
                    </span>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>
                    <p class="text-gray-500 text-sm mb-2">{{ Str::limit($product->description, 50) }}</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-xl font-bold text-red-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                        <a href="{{ route('product.show', $product->slug) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection