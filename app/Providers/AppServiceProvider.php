<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Company;
use App\Models\CompanyEmployee;
use App\Models\ContractedService;
use App\Models\User;
use App\Models\WorkReport;
use App\Models\WorkspaceExecutor;
use App\Policies\AddressPolicy;
use App\Policies\CompanyEmployeePolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ContractedServicePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkReportPolicy;
use App\Policies\WorkspaceExecutorPolicy;
use App\Services\IAddressService;
use App\Services\IBuildingPermitService;
use App\Services\ICompanyEmployeeService;
use App\Services\ICompanyService;
use App\Services\IExecutorService;
use App\Services\Implementations\AddressService;
use App\Services\Implementations\BuildingPermitService;
use App\Services\Implementations\CompanyEmployeeService;
use App\Services\Implementations\CompanyService;
use App\Services\Implementations\ExecutorService;
use App\Services\Implementations\RoleService;
use App\Services\Implementations\UserRegistrationService;
use App\Services\Implementations\UserService;
use App\Services\Implementations\WorkReportService;
use App\Services\Implementations\WorkspaceInvitationService;
use App\Services\Implementations\WorkspaceRedirectService;
use App\Services\Implementations\WorkspaceUserService;
use App\Services\IRoleService;
use App\Services\IUserRegistrationService;
use App\Services\IUserService;
use App\Services\IWorkReportService;
use App\Services\IWorkspaceInvitationService;
use App\Services\IWorkspaceRedirectService;
use App\Services\IWorkspaceUserService;
use Illuminate\Support\Facades\Gate;
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
        $this->app->bind(IRoleService::class, RoleService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IUserRegistrationService::class, UserRegistrationService::class);
        $this->app->bind(ICompanyService::class, CompanyService::class);
        $this->app->bind(IWorkReportService::class, WorkReportService::class);
        $this->app->bind(ICompanyEmployeeService::class, CompanyEmployeeService::class);
        $this->app->bind(IWorkspaceInvitationService::class, WorkspaceInvitationService::class);
        $this->app->bind(IWorkspaceRedirectService::class, WorkspaceRedirectService::class);
        $this->app->bind(IWorkspaceUserService::class, WorkspaceUserService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(WorkReport::class, WorkReportPolicy::class);
        Gate::policy(ContractedService::class, ContractedServicePolicy::class);
        Gate::policy(WorkspaceExecutor::class, WorkspaceExecutorPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(CompanyEmployee::class, CompanyEmployeePolicy::class);
        Gate::policy(Address::class, AddressPolicy::class);
    }
}
