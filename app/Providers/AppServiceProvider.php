<?php

namespace App\Providers;

use App\Services\Implementations\UserService;
use App\Services\Implementations\WorkReportEntryService;
use App\Services\IUserService;
use App\Services\IWorkReportEntryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IWorkReportEntryService::class, WorkReportEntryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
