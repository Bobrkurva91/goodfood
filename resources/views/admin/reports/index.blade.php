@extends('layouts.shop')

@section('title', 'Отчеты')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">📊 Отчеты и аналитика</h1>

    <!-- Основная статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Всего заказов</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Выручка</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($totalRevenue, 0, ',', ' ') }} ₽</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ruble-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Средний чек</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($averageOrderValue, 0, ',', ' ') }} ₽</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Пользователей</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    <p class="text-xs text-green-600">+{{ $newUsersThisMonth }} за месяц</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика по статусам заказов -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Статусы заказов</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Ожидают обработки</span>
                    <div class="flex items-center">
                        <span class="font-bold mr-2">{{ $statusStats['pending'] }}</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($statusStats['pending'] / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">В обработке</span>
                    <div class="flex items-center">
                        <span class="font-bold mr-2">{{ $statusStats['processing'] }}</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($statusStats['processing'] / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Выполнены</span>
                    <div class="flex items-center">
                        <span class="font-bold mr-2">{{ $statusStats['completed'] }}</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($statusStats['completed'] / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Отменены</span>
                    <div class="flex items-center">
                        <span class="font-bold mr-2">{{ $statusStats['cancelled'] }}</span>
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalOrders > 0 ? ($statusStats['cancelled'] / $totalOrders * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Топ товаров -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">🏆 Топ-10 товаров по продажам</h2>
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="text-gray-500 w-6">{{ $index + 1 }}.</span>
                        <span class="text-gray-700">{{ $product->product_name }}</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">{{ $product->total_quantity }} шт.</span>
                        <span class="text-sm font-semibold text-green-600">{{ number_format($product->total_revenue, 0, ',', ' ') }} ₽</span>
                    </div>
                </div>
                @endforeach

                @if($topProducts->isEmpty())
                    <p class="text-gray-500 text-center py-4">Нет данных о продажах</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Динамика по дням -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">📈 Динамика продаж (последние 30 дней)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Дата</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Заказов</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Выручка</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStats as $day)
                    <tr class="border-b">
                        <td class="px-4 py-2 text-sm text-gray-700">{{ \Carbon\Carbon::parse($day->date)->format('d.m.Y') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $day->count }}</td>
                        <td class="px-4 py-2 text-sm font-semibold text-green-600">{{ number_format($day->revenue, 0, ',', ' ') }} ₽</td>
                    </tr>
                    @endforeach

                    @if($dailyStats->isEmpty())
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">Нет данных за последние 30 дней</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ссылки на детальные отчеты -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.reports.sales') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">📋 Отчет по продажам</h3>
                    <p class="text-blue-100 mt-1">Детальная статистика по заказам за период</p>
                </div>
                <i class="fas fa-chart-simple text-3xl opacity-50"></i>
            </div>
        </a>

        <a href="{{ route('admin.reports.products') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">🏷️ Отчет по товарам</h3>
                    <p class="text-green-100 mt-1">Популярность и продажи товаров</p>
                </div>
                <i class="fas fa-box text-3xl opacity-50"></i>
            </div>
        </a>
    </div>
</div>
@endsection
