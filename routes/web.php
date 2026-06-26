<?php

use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierAuthController;
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

// ==================== ОФОРМЛЕНИЕ ЗАКАЗА ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// ==================== ОПЛАТА (ЮKassa) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/create/{order}', [PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/page/{order}', [PaymentController::class, 'page'])->name('payment.page');
    Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Webhook для ЮKassa (без auth, доступен извне)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// ==================== ИЗБРАННОЕ ====================
Route::middleware(['auth'])->prefix('wishlist')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/count', [WishlistController::class, 'count'])->name('wishlist.count');
});

// ==================== ОТЗЫВЫ О ТОВАРАХ ====================
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');

// ==================== ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ==================== КУРЬЕР ====================
Route::prefix('courier')->name('courier.')->group(function () {
    Route::get('/login', [CourierAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CourierAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [CourierAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:courier')->group(function () {
        Route::get('/dashboard', [CourierController::class, 'dashboard'])->name('dashboard');
        Route::post('/orders/{order}/took', [CourierController::class, 'took'])->name('took');
        Route::post('/orders/{order}/on-way', [CourierController::class, 'onWay'])->name('on_way');
        Route::post('/orders/{order}/delivered', [CourierController::class, 'delivered'])->name('delivered');
    });
});

// ==================== АДМИН-ПАНЕЛЬ (ТОЛЬКО ДЛЯ АДМИНОВ) ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Главная
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

    // Доставка и курьеры
    Route::post('/orders/{order}/assign-courier', [App\Http\Controllers\Admin\OrderController::class, 'assignCourier'])->name('admin.orders.assignCourier');
    Route::post('/orders/{order}/update-delivery', [App\Http\Controllers\Admin\OrderController::class, 'updateDeliveryStatus'])->name('admin.orders.updateDelivery');

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

    // Отзывы о товарах (модерация)
    Route::get('/reviews', [App\Http\Controllers\Admin\AdminController::class, 'reviews'])->name('admin.reviews');
    Route::post('/reviews/{id}/approve', [App\Http\Controllers\Admin\AdminController::class, 'approveReview'])->name('admin.reviews.approve');
    Route::post('/reviews/{id}/reject', [App\Http\Controllers\Admin\AdminController::class, 'rejectReview'])->name('admin.reviews.reject');

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
