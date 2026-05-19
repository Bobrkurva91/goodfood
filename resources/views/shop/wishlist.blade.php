@extends('layouts.shop')

@section('title', 'Избранное')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">❤️ Избранное</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($wishlistItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group">
                <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                    @if($item->product->image)
                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    @endif

                    <!-- Кнопка удаления из избранного -->
                    <form method="POST" action="{{ route('wishlist.remove', $item->product) }}" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:bg-red-50 text-red-600">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $item->product->name }}</h3>
                    <p class="text-gray-500 text-sm mb-2">{{ Str::limit($item->product->description, 60) }}</p>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-xl font-bold text-red-600">{{ number_format($item->product->price, 0, ',', ' ') }} ₽</span>
                        <a href="{{ route('product.show', $item->product->slug) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="far fa-heart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-6">У вас пока нет избранных товаров</p>
            <a href="{{ route('catalog') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                Перейти в каталог
            </a>
        </div>
    @endif
</div>
@endsection
