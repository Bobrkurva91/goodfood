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
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-6xl text-gray-400"></i>
                    @endif
                </div>
            </div>

            <!-- Информация о товаре -->
            <div class="md:w-1/2 p-8">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <a href="{{ route('catalog', ['category' => $product->category_id]) }}" class="text-red-600 text-sm hover:underline">
                            {{ $product->category->name }}
                        </a>
                        <h1 class="text-3xl font-bold text-gray-800 mt-1">{{ $product->name }}</h1>
                    </div>

                    <!-- Кнопка "В избранное" -->
                    @auth
                        @if(Auth::user()->isInWishlist($product->id))
                            <form method="POST" action="{{ route('wishlist.remove', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white border border-red-600 rounded-full p-3 shadow-md hover:bg-red-50 transition">
                                    <i class="fas fa-heart text-red-600 text-xl"></i>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('wishlist.add', $product) }}">
                                @csrf
                                <button type="submit" class="bg-white border border-gray-300 rounded-full p-3 shadow-md hover:border-red-600 transition">
                                    <i class="far fa-heart text-gray-500 text-xl hover:text-red-600"></i>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                @if($product->is_on_sale)
                    <div class="mb-4">
                        <span class="bg-red-600 text-white text-sm font-bold px-3 py-1 rounded">Акция</span>
                    </div>
                @endif

                @if($product->is_new)
                    <div class="mb-4">
                        <span class="bg-green-600 text-white text-sm font-bold px-3 py-1 rounded">Новинка</span>
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
                    @if($product->stock > 0)
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
                        <button disabled class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                            <i class="fas fa-ban mr-2"></i> Нет в наличии
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block text-center w-full bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                        Войдите, чтобы купить
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Блок отзывов -->
<div class="mt-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">⭐ Отзывы о товаре</h2>

    <!-- Средний рейтинг -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <div class="text-4xl font-bold text-gray-800 mr-4">{{ $product->averageRating() }}</div>
            <div>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->averageRating()))
                            <i class="fas fa-star text-yellow-500 text-xl"></i>
                        @elseif($i - 0.5 <= $product->averageRating())
                            <i class="fas fa-star-half-alt text-yellow-500 text-xl"></i>
                        @else
                            <i class="far fa-star text-gray-300 text-xl"></i>
                        @endif
                    @endfor
                </div>
                <p class="text-gray-500 text-sm">На основе {{ $product->reviewsCount() }} {{ trans_choice('отзыва|отзывов|отзывов', $product->reviewsCount()) }}</p>
            </div>
        </div>
    </div>

    <!-- Список отзывов -->
    @foreach($product->reviews as $review)
    <div class="border-b border-gray-200 pb-4 mb-4">
        <div class="flex items-center mb-2">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <span class="text-red-600 font-bold">{{ substr($review->user->name, 0, 1) }}</span>
            </div>
            <div>
                <p class="font-semibold">{{ $review->user->name }}</p>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fas fa-star text-yellow-500 text-sm"></i>
                        @else
                            <i class="far fa-star text-gray-300 text-sm"></i>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
        <p class="text-gray-600">{{ $review->comment }}</p>
        <p class="text-gray-400 text-sm mt-2">{{ $review->created_at->format('d.m.Y') }}</p>
    </div>
    @endforeach

    <!-- Форма добавления отзыва -->
    @auth
        @if(Auth::user()->hasPurchasedProduct($product->id))
            @php
                $userReview = $product->allReviews()->where('user_id', Auth::id())->first();
            @endphp

            @if(!$userReview)
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-3">Оставить отзыв</h3>
                    <form method="POST" action="{{ route('review.store', $product) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-gray-700 mb-2">Ваша оценка</label>
                            <div class="flex items-center space-x-2" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star text-2xl cursor-pointer hover:text-yellow-500" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-value" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-gray-700 mb-2">Ваш отзыв</label>
                            <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required></textarea>
                        </div>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            Отправить отзыв
                        </button>
                    </form>
                </div>
            @elseif($userReview->status == 'pending')
                <div class="mt-6 bg-yellow-50 rounded-lg p-4 text-yellow-700">
                    <i class="fas fa-clock mr-2"></i> Вы уже оставили отзыв. Он проходит модерацию.
                </div>
            @elseif($userReview->status == 'approved')
                <div class="mt-6 bg-green-50 rounded-lg p-4 text-green-700">
                    <i class="fas fa-check-circle mr-2"></i> Спасибо за ваш отзыв!
                </div>
            @endif
        @else
            <div class="mt-6 bg-gray-50 rounded-lg p-4 text-gray-500">
                <i class="fas fa-info-circle mr-2"></i> Вы можете оставить отзыв только после покупки этого товара.
            </div>
        @endif
    @else
        <div class="mt-6 bg-gray-50 rounded-lg p-4 text-gray-500">
            <a href="{{ route('login') }}" class="text-red-600 hover:underline">Войдите</a>, чтобы оставить отзыв.
        </div>
    @endauth
</div>

<script>
    // Скрипт для звезд рейтинга
    const stars = document.querySelectorAll('#rating-stars i');
    const ratingInput = document.getElementById('rating-value');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            ratingInput.value = value;

            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });
</script>

    <!-- Похожие товары -->
    @if($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Похожие товары</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group relative">
                <div class="h-40 bg-gray-200 flex items-center justify-center relative">
                    @if($related->image)
                        <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-image text-3xl text-gray-400"></i>
                    @endif

                    <!-- Кнопка "В избранное" для похожих товаров -->
                    @auth
                        @if(Auth::user()->isInWishlist($related->id))
                            <form method="POST" action="{{ route('wishlist.remove', $related) }}" class="absolute top-2 left-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white rounded-full p-1 shadow-md hover:bg-red-50">
                                    <i class="fas fa-heart text-red-600 text-sm"></i>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('wishlist.add', $related) }}" class="absolute top-2 left-2">
                                @csrf
                                <button type="submit" class="bg-white rounded-full p-1 shadow-md hover:bg-red-50">
                                    <i class="far fa-heart text-gray-600 text-sm hover:text-red-600"></i>
                                </button>
                            </form>
                        @endif
                    @endauth
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
