<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Auth\Http\Responses\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();
        $panel = Filament::getCurrentPanel();

        // Check if user has a default tenant
        $defaultTenant = $user->getDefaultTenant($panel);
        
        if ($defaultTenant) {
            // Redirect to the default workspace
            return redirect()->to(
                Filament::getTenantUrl($defaultTenant, $panel)
            );
        }

        // Fallback to first available tenant if no default
        $tenants = $user->getTenants($panel);
        if ($tenants->isNotEmpty()) {
            return redirect()->to(
                Filament::getTenantUrl($tenants->first(), $panel)
            );
        }

        // No tenants available - redirect to regular panel
        return redirect()->intended(Filament::getUrl());
    }
}
