@extends('layouts.shop')

@section('title', 'Управление отзывами')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📝 Управление отзывами</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Товар</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Рейтинг</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Отзыв</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td class="px-6 py-4">{{ $review->product->name }}</td>
                    <td class="px-6 py-4">{{ $review->user->name }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                                @else
                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                @endif
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 max-w-xs">{{ Str::limit($review->comment, 50) }}</td>
                    <td class="px-6 py-4">
                        @if($review->status == 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">На модерации</span>
                        @elseif($review->status == 'approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Одобрен</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отклонен</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($review->status == 'pending')
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">✅ Одобрить</button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800">❌ Отклонить</button>
                            </form>
                        @else
                            <span class="text-gray-400">Обработан</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
