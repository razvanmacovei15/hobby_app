<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

interface IWorkspaceRedirectService
{
    public function redirectToSpecificWorkspace(User $user, Workspace $workspace): RedirectResponse;
}
