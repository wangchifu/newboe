<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;

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
    // 所有的初始化邏輯都應該放在這個類內部的 boot 方法中
        URL::forceRootUrl(config('app.url'));

        if (str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
}
