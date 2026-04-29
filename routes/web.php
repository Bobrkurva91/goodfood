<?php

use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== МАРШРУТЫ МАГАЗИНА ====================
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/catalog', [ShopController::class, 'catalog'])->name('catalog');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');

// ==================== КОРЗИНА ====================
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add/{product}', 'add')->name('cart.add');
    Route::patch('/cart/update/{item}', 'update')->name('cart.update');
    Route::delete('/cart/remove/{item}', 'remove')->name('cart.remove');
    Route::delete('/cart/clear', 'clear')->name('cart.clear');
    Route::get('/cart/count', 'count')->name('cart.count');
});

// ==================== ОФОРМЛЕНИЕ ЗАКАЗА И ОПЛАТА ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{order}', [PaymentController::class, 'index'])->name('payment.page');
});

// ==================== ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ==================== АДМИН-ПАНЕЛЬ (ТОЛЬКО ДЛЯ АДМИНОВ) ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Главная админки
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // Управление товарами
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    // Управление заказами
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
        'destroy' => 'admin.orders.destroy',
    ]);

    // Дополнительные маршруты для заказов (добавление/удаление товаров)
    Route::post('/orders/{order}/add-item', [App\Http\Controllers\Admin\OrderController::class, 'addItem'])->name('admin.orders.addItem');
    Route::delete('/orders/{order}/remove-item/{item}', [App\Http\Controllers\Admin\OrderController::class, 'removeItem'])->name('admin.orders.removeItem');

    // Управление категориями
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Управление пользователями
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'admin.users.index',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Отчеты
    Route::prefix('reports')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('admin.reports.sales');
        Route::get('/products', [App\Http\Controllers\Admin\ReportController::class, 'products'])->name('admin.reports.products');
        Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');
    });
});

// ==================== КАСТОМНЫЙ ЛОГИН ====================
Route::post('/login', [CustomLoginController::class, 'login'])->name('login');

// ==================== СТАНДАРТНЫЕ МАРШРУТЫ BREEZE ====================
require __DIR__.'/auth.php';
