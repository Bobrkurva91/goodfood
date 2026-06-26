<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход для курьера - GooDFooD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; }
        .login-card { max-width: 420px; margin: 80px auto; border-radius: 16px; overflow: hidden; }
        .login-header { background: linear-gradient(135deg, #1e293b, #0f172a); padding: 24px; text-align: center; color: white; }
        .login-body { padding: 32px; background: white; }
        .btn-courier { background: #dc2626; color: white; border: none; padding: 12px; font-weight: bold; border-radius: 10px; transition: 0.3s; }
        .btn-courier:hover { background: #b91c1c; color: white; }
        .courier-icon { font-size: 48px; margin-bottom: 8px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card shadow-lg">
            <div class="login-header">
                <span class="courier-icon">🚚</span>
                <h3 class="mb-0">GooDFooD Доставка</h3>
                <p class="text-muted mb-0" style="color:#94a3b8 !important;">Вход для курьеров</p>
            </div>
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/courier/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg"
                               placeholder="petr@goodfood.ru" value="petr@goodfood.ru" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Пароль</label>
                        <input type="password" name="password" class="form-control form-control-lg"
                               placeholder="********" value="password123" required>
                    </div>
                    <button class="btn-courier w-100 btn-lg">Войти в систему</button>
                </form>
                <p class="text-center text-muted mt-3 small">
                    Доступ только для курьеров GooDFooD
                </p>
            </div>
        </div>
    </div>
</body>
</html>
