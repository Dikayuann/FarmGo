<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notification count with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('notificationCount', Auth::user()->notifications()->where('status', 'belum_dibaca')->count());
            }
        });

        // Only force HTTPS in production environment
        // This prevents "Invalid request (Unsupported SSL request)" errors in local development
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }

}
