<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GooDFooD - @yield('title', 'Магазин вкусной еды')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Навигация -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-bold text-red-600">
                            🍔 GooDFooD
                        </a>
                        <div class="hidden md:flex ml-10 space-x-4">
                            <a href="/" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Главная</a>
                            <a href="/catalog" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Каталог</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-red-600 relative">
    <i class="fas fa-shopping-cart text-xl"></i>
    <span id="cart-count" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
        0
    </span>
</a>
                            <div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-red-600 focus:outline-none">
        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <span class="font-medium">{{ Auth::user()->name }}</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100" style="display: none;">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
            <i class="fas fa-user mr-2"></i> Мой профиль
        </a>
        
        @if(Auth::user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
        <i class="fas fa-chart-line mr-2"></i> Админ-панель
    </a>
@endif
        
        <div class="border-t my-1"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                <i class="fas fa-sign-out-alt mr-2"></i> Выйти
            </button>
        </form>
    </div>
</div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">Вход</a>
                            <a href="{{ route('register') }}" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">Регистрация</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Основной контент -->
        <main>
            @yield('content')
        </main>

        <!-- Футер -->
        <footer class="bg-white shadow-lg mt-auto py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500">
                <p>© 2026 GooDFooD - Вкусная еда с доставкой</p>
            </div>
        </footer>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    function updateCartCount() {
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const countSpan = document.getElementById('cart-count');
                if (countSpan) {
                    countSpan.textContent = data.count;
                    countSpan.style.display = data.count > 0 ? 'flex' : 'none';
                }
            });
    }
    
    // Обновляем при загрузке страницы
    document.addEventListener('DOMContentLoaded', updateCartCount);
    
    // Обновляем после добавления товара (если форма отправляется)
    document.querySelectorAll('form[action*="/cart/add/"]').forEach(form => {
        form.addEventListener('submit', function() {
            setTimeout(updateCartCount, 500);
        });
    });
</script>
</body>
</html>