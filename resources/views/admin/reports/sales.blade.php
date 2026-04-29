@extends('layouts.shop')

@section('title', 'Отчет по продажам')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">📋 Отчет по продажам</h1>
    </div>

    <!-- Фильтр по датам -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-gray-700 font-medium mb-2">С даты</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">По дату</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-filter mr-2"></i> Применить фильтр
                </button>
            </div>
        </form>
    </div>

    <!-- Сводка -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <p class="text-gray-500 text-sm">Всего заказов</p>
            <p class="text-3xl font-bold text-gray-800">{{ $summary['total_orders'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <p class="text-gray-500 text-sm">Выручка</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($summary['total_revenue'], 0, ',', ' ') }} ₽</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <p class="text-gray-500 text-sm">Средний чек</p>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($summary['avg_order'], 0, ',', ' ') }} ₽</p>
        </div>
    </div>

    <!-- Экспорт -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.reports.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-download mr-2"></i> Экспорт в CSV
        </a>
    </div>

    <!-- Таблица заказов -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">№ заказа</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Покупатель</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Сумма</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Статус</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100' }}">
                            @switch($order->status)
                                @case('pending') Ожидает @break
                                @case('processing') В обработке @break
                                @case('completed') Выполнен @break
                                @case('cancelled') Отменен @break
                                @default {{ $order->status }}
                            @endswitch
                        </span>
                    </td>
                </tr>
                @endforeach

                @if($orders->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">Нет заказов за выбранный период</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
