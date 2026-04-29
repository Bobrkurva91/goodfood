<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Перенаправление после входа на главную страницу
        if (Auth::check()) {
            // Это сработает при редиректе
        }
    }
}