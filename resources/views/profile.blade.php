@extends('layouts.shop')

@section('title', 'Мой профиль')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Боковая панель -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-white text-3xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex items-center text-gray-600 mb-3">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="text-sm">Регистрация: {{ Auth::user()->created_at->format('d.m.Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Форма редактирования профиля -->
        <div class="lg:col-span-2">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Форма обновления данных -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Личные данные</h3>
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Имя</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Сохранить изменения
                    </button>
                </form>
            </div>
            
            <!-- Форма смены пароля -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Смена пароля</h3>
                
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Текущий пароль</label>
                        <input type="password" name="current_password" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Новый пароль</label>
                        <input type="password" name="password" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Подтверждение пароля</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                    
                    <button type="submit" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        Сменить пароль
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection