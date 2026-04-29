@extends('layouts.shop')

@section('title', 'Каталог')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Каталог товаров</h1>
    
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Фильтры -->
        <div class="md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="font-semibold text-lg mb-4">Категории</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('catalog') }}" class="text-gray-600 hover:text-red-600 transition {{ !request('category') ? 'text-red-600 font-semibold' : '' }}">
                            Все товары
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('catalog', ['category' => $category->id, 'sort' => $sort]) }}" 
                           class="text-gray-600 hover:text-red-600 transition {{ request('category') == $category->id ? 'text-red-600 font-semibold' : '' }}">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <!-- Товары -->
        <div class="md:w-3/4">
            <!-- Сортировка -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex justify-between items-center">
                <span class="text-gray-600">Найдено: {{ $products->total() }} товаров</span>
                <select class="border rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-red-500" onchange="window.location.href=this.value">
                    <option value="{{ route('catalog', ['category' => request('category'), 'sort' => 'newest']) }}" {{ $sort == 'newest' ? 'selected' : '' }}>
                        Сначала новинки
                    </option>
                    <option value="{{ route('catalog', ['category' => request('category'), 'sort' => 'price_asc']) }}" {{ $sort == 'price_asc' ? 'selected' : '' }}>
                        По возрастанию цены
                    </option>
                    <option value="{{ route('catalog', ['category' => request('category'), 'sort' => 'price_desc']) }}" {{ $sort == 'price_desc' ? 'selected' : '' }}>
                        По убыванию цены
                    </option>
                </select>
            </div>
            
            <!-- Сетка товаров -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group">
                    <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        @endif
                        @if($product->is_on_sale)
                            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                Акция
                            </span>
                        @endif
                        @if($product->is_new)
                            <span class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded">
                                Новинка
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>
                        <p class="text-gray-500 text-sm mb-2">{{ Str::limit($product->description, 60) }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-xl font-bold text-red-600">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                            <a href="{{ route('product.show', $product->slug) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Товары не найдены</p>
                </div>
                @endforelse
            </div>
            
            <!-- Пагинация -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection