<?php

namespace App\Providers;

use App\Services\IExecutorService;
use App\Services\Implementations\ExecutorService;
use App\Services\IAddressService;
use App\Services\IBuildingPermitService;
use App\Services\ICompanyService;
use App\Services\Implementations\WorkReportService;
use App\Services\IUserService;
use App\Services\IUserWorkspaceService;
use App\Services\Implementations\AddressService;
use App\Services\Implementations\BuildingPermitService;
use App\Services\Implementations\CompanyService;
use App\Services\Implementations\UserService;
use App\Services\Implementations\UserWorkspaceService;
use App\Services\IWorkReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IExecutorService::class, ExecutorService::class);
        $this->app->bind(IAddressService::class, AddressService::class);
        $this->app->bind(IBuildingPermitService::class, BuildingPermitService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IUserWorkspaceService::class, UserWorkspaceService::class);
        $this->app->bind(ICompanyService::class, CompanyService::class);
        $this->app->bind(IWorkReportService::class, WorkReportService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
