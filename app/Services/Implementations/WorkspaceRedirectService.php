<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Models\Workspace;
use App\Services\IWorkspaceRedirectService;
use Illuminate\Http\RedirectResponse;

class WorkspaceRedirectService implements IWorkspaceRedirectService
{
    public function redirectToSpecificWorkspace(User $user, Workspace $workspace): RedirectResponse
    {
        // Construct the workspace URL manually to ensure it's correct
        // Based on the route structure: /{tenant}/dashboard
        $workspaceUrl = '/'.$workspace->id;

        // Debug: Log the URL being generated
        \Log::info('WorkspaceRedirectService redirect', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'target_workspace_id' => $workspace->id,
            'target_workspace_name' => $workspace->name,
            'manual_workspace_url' => $workspaceUrl,
        ]);

        return redirect()->to($workspaceUrl);
    }
}
