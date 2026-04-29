@extends('layouts.shop')

@section('title', 'Пользователь: ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Пользователь: {{ $user->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Информация о пользователе -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>

                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Роль:</span>
                        <span class="font-medium">
                            @if($user->isAdmin())
                                <span class="text-purple-600">Администратор</span>
                            @else
                                <span class="text-gray-600">Пользователь</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Дата регистрации:</span>
                        <span class="font-medium">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Всего заказов:</span>
                        <span class="font-medium">{{ $ordersCount }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-2 pt-2 border-t">
                        <span class="text-gray-500">Потрачено всего:</span>
                        <span class="font-bold text-red-600">{{ number_format($totalSpent, 0, ',', ' ') }} ₽</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.users.edit', $user) }}" class="block w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition text-center">
                        <i class="fas fa-edit mr-2"></i> Редактировать
                    </a>
                </div>
            </div>
        </div>

        <!-- Заказы пользователя -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">📋 Заказы пользователя</h2>

                @if($user->orders->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->orders as $order)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">Заказ #{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600">{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</p>
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
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">У пользователя пока нет заказов</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
