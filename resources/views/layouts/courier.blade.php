<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GooDFooD - @yield('title', 'Курьер')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ route('courier.dashboard') }}">
                🚚 GooDFooD Курьер
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="mt-5 py-3 bg-light text-center text-muted">
        <p class="mb-0 small">© 2026 GooDFooD Доставка</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
