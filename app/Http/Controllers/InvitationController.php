<?php

namespace App\Http\Controllers;

use App\Models\Permission\Role;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Services\IWorkspaceInvitationService;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class InvitationController extends Controller
{
    private IWorkspaceInvitationService $invitationService;

    public function __construct(IWorkspaceInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    private function redirectToWorkspace(Workspace $workspace): RedirectResponse
    {
        $panel = Filament::getCurrentPanel();
        if (!$panel) {
            return redirect('/');
        }
        
        return redirect()->to(
            $panel->getUrl(tenant: $workspace)
        );
    }

    private function redirectToUserDashboard(): RedirectResponse
    {
        $user = Auth::user();
        $panel = Filament::getCurrentPanel();
        
        if (!$panel || !$user) {
            return redirect('/');
        }

        $defaultTenant = $user->getDefaultTenant($panel);
        if ($defaultTenant) {
            return redirect()->to($panel->getUrl(tenant: $defaultTenant));
        }

        $tenants = $user->getTenants($panel);
        if ($tenants->isNotEmpty()) {
            return redirect()->to($panel->getUrl(tenant: $tenants->first()));
        }

        return redirect()->to($panel->getUrl());
    }

    public function showRegistrationForm(string $token)
    {
        // If user is already logged in, redirect to their workspace
        if (Auth::check()) {
            return $this->redirectToUserDashboard();
        }

        $invitation = $this->getInvitationOrFail($token);
        
        if ($invitation instanceof RedirectResponse) {
            return $invitation;
        }

        $invitation->load(['workspace', 'invitedBy']);
        $user = User::findOrFail($invitation->invitee_id);

        return view('auth.register-from-invitation', [
            'invitation' => $invitation,
            'token' => $token,
            'prefilledData' => [
                'first_name' => $user->first_name ?? 'User',
                'last_name' => $user->last_name ?? 'Test',
                'email' => $user->email,
            ]
        ]);
    }

    public function registerFromInvitation(Request $request, string $token)
    {
        $invitation = $this->getInvitationOrFail($token);
        
        if ($invitation instanceof RedirectResponse) {
            return $invitation;
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::findOrFail($invitation->invitee_id);
            
            // Update user password
            $user->update([
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Accept the invitation
            $result = $this->invitationService->acceptInvitation($token);

            if (!$result['success']) {
                return back()->withErrors(['general' => $result['message']])->withInput();
            }
            
            // Log the user in
            Auth::login($user);

            // Redirect to the workspace they were invited to join
            return $this->redirectToWorkspace($invitation->workspace)
                ->with('success', "Welcome! You've successfully joined {$invitation->workspace->name}.");

        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function acceptInvitation(string $token)
    {
        return redirect()->route('register.from-invitation', $token);
    }

    private function getInvitationOrFail(string $token): WorkspaceInvitation|RedirectResponse
    {
        // First try to find a pending invitation
        $invitation = WorkspaceInvitation::findByToken($token);
        
        if ($invitation) {
            return $invitation;
        }

        // If no pending invitation found, check if it was already accepted
        $acceptedInvitation = WorkspaceInvitation::where('token', $token)->first();
        
        if ($acceptedInvitation && $acceptedInvitation->isAccepted()) {
            return redirect()->route('filament.admin.auth.login')
                ->with('info', 'This invitation has already been used. Please log in with your credentials.');
        }

        // If invitation doesn't exist at all, redirect to login with error
        return redirect()->route('filament.admin.auth.login')->withErrors([
            'email' => 'This invitation link is invalid or has expired.'
        ]);
    }
}
