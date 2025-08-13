<?php

namespace App\Providers;

use App\Services\IExecutorService;
use App\Services\Implementations\ExecutorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IExecutorService::class, ExecutorService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
