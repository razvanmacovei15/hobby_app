<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();
        $panel = Filament::getCurrentPanel();

        // Check if user has a default tenant
        $defaultTenant = $user->getDefaultTenant();

        if ($defaultTenant) {
            // Redirect to the default workspace
            return redirect()->to(
                $panel->getUrl(tenant: $defaultTenant)
            );
        }

        // Fallback to first available tenant if no default
        $tenants = $user->getTenants($panel);
        if ($tenants->isNotEmpty()) {
            return redirect()->to(
                $panel->getUrl(tenant: $tenants->first())
            );
        }

        // No tenants available - redirect to regular panel
        return redirect()->intended(Filament::getUrl());
    }
}
