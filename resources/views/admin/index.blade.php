@extends('layouts.shop')

@section('title', 'Админ-панель')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">👑 Админ-панель</h1>

    <!-- Статистика - 4 карточки в ряд -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Всего заказов</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalOrders ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ожидают обработки</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingOrders ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Товаров в каталоге</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalProducts ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Пользователей</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Разделы админки - 3 карточки в ряд -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Товары -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold ml-3">Товары</h2>
            </div>
            <p class="text-gray-600 mb-4 text-sm">Управление каталогом товаров: добавление, редактирование, удаление</p>
            <a href="{{ route('admin.products.index') }}" class="text-red-600 hover:underline inline-flex items-center text-sm font-medium">
                Управление →
            </a>
        </div>

        <!-- Категории -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-tags text-green-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold ml-3">Категории</h2>
            </div>
            <p class="text-gray-600 mb-4 text-sm">Управление категориями товаров</p>
            <a href="{{ route('admin.categories.index') }}" class="text-red-600 hover:underline inline-flex items-center text-sm font-medium">
                Управление →
            </a>
        </div>

        <!-- Заказы -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold ml-3">Заказы</h2>
            </div>
            <p class="text-gray-600 mb-4 text-sm">Просмотр и управление заказами клиентов</p>
            <a href="{{ route('admin.orders.index') }}" class="text-red-600 hover:underline inline-flex items-center text-sm font-medium">
                Управление →
            </a>
        </div>

        <!-- Пользователи -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold ml-3">Пользователи</h2>
            </div>
            <p class="text-gray-600 mb-4 text-sm">Управление пользователями и их правами</p>
            <a href="{{ route('admin.users.index') }}" class="text-red-600 hover:underline inline-flex items-center text-sm font-medium">
                Управление →
            </a>
        </div>

        <!-- Отчеты -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition border border-gray-100">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-red-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold ml-3">Отчеты</h2>
            </div>
            <p class="text-gray-600 mb-4 text-sm">Статистика продаж и аналитика</p>
            <a href="{{ route('admin.reports.index') }}" class="text-red-600 hover:underline inline-flex items-center text-sm font-medium">
                Управление →
            </a>
        </div>
    </div>
</div>
@endsection
