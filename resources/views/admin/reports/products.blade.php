@extends('layouts.shop')

@section('title', 'Отчет по товарам')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">🏷️ Отчет по товарам</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Фото</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Товар</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Категория</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Продано</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Выручка</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">В наличии</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-sm"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $product->name }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold text-gray-700">{{ $product->order_items_sum_quantity ?? 0 }} шт.</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-green-600">{{ number_format($product->order_items_sum_total ?? 0, 0, ',', ' ') }} ₽</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="text-sm text-gray-500">{{ $product->stock }} шт.</span>
                        </td>
                    </tr>
                    @endforeach

                    @if($products->isEmpty())
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            Нет данных о товарах
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
