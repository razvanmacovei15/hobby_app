<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Services\IUserService;
use App\Services\IWorkspaceInvitationService;
use App\Services\IWorkspaceRedirectService;
use App\Services\IWorkspaceUserService;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class InvitationController extends Controller
{
    private IWorkspaceInvitationService $invitationService;

    private IUserService $userService;

    private IWorkspaceUserService $workspaceUserService;

    private IWorkspaceRedirectService $workspaceRedirectService;

    public function __construct(
        IWorkspaceInvitationService $invitationService,
        IUserService $userService,
        IWorkspaceUserService $workspaceUserService,
        IWorkspaceRedirectService $workspaceRedirectService
    ) {
        $this->invitationService = $invitationService;
        $this->userService = $userService;
        $this->workspaceUserService = $workspaceUserService;
        $this->workspaceRedirectService = $workspaceRedirectService;
    }

    private function redirectToWorkspace(Workspace $workspace): RedirectResponse
    {
        $panel = Filament::getCurrentPanel();
        if (! $panel) {
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

        if (! $panel || ! $user) {
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
        $invitation = $this->getInvitationOrFail($token);

        if ($invitation instanceof RedirectResponse) {
            return $invitation;
        }

        $invitation->load(['workspace', 'invitedBy']);
        $inviteeUser = User::findOrFail($invitation->invitee_id);

        // If user is already logged in, handle accordingly
        if (Auth::check()) {
            $currentUser = Auth::user();

            // Check if logged-in user's email matches invitation email
            if ($currentUser->email === $inviteeUser->email) {
                // Check if already in workspace
                if ($this->workspaceUserService->isUserInWorkspace($currentUser, $invitation->workspace)) {
                    return $this->redirectToWorkspace($invitation->workspace)
                        ->with('info', 'You are already a member of this workspace.');
                } else {
                    // Accept invitation directly and redirect
                    $result = $this->invitationService->acceptInvitationForExistingUser($token, $currentUser);

                    if ($result['success']) {
                        return $this->workspaceRedirectService->redirectToSpecificWorkspace($currentUser, $invitation->workspace)
                            ->with('success', $result['message']);
                    } else {
                        return redirect()->route('filament.admin.auth.login')
                            ->withErrors(['general' => $result['message']]);
                    }
                }
            } else {
                // Email mismatch - show logout option
                return view('auth.email-mismatch', [
                    'invitation' => $invitation,
                    'token' => $token,
                    'currentUserEmail' => $currentUser->email,
                    'invitedEmail' => $inviteeUser->email,
                ]);
            }
        }

        // Check if user exists and has an active password
        $existingUser = $this->userService->checkExistingUserByEmail($inviteeUser->email);
        $isExistingUser = $existingUser && $this->userService->hasActivePassword($existingUser);

        return view('auth.register-from-invitation', [
            'invitation' => $invitation,
            'token' => $token,
            'isExistingUser' => $isExistingUser,
            'prefilledData' => [
                'first_name' => $inviteeUser->first_name ?? 'User',
                'last_name' => $inviteeUser->last_name ?? 'Test',
                'email' => $inviteeUser->email,
            ],
        ]);
    }

    public function registerFromInvitation(Request $request, string $token)
    {
        $invitation = $this->getInvitationOrFail($token);

        if ($invitation instanceof RedirectResponse) {
            return $invitation;
        }

        $inviteeUser = User::findOrFail($invitation->invitee_id);
        $existingUser = $this->userService->checkExistingUserByEmail($inviteeUser->email);
        $isExistingUser = $existingUser && $this->userService->hasActivePassword($existingUser);

        if ($isExistingUser) {
            // Handle existing user login
            $validator = Validator::make($request->all(), [
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Verify password
            if (! Hash::check($request->password, $existingUser->password)) {
                return back()->withErrors(['password' => 'The password is incorrect.'])->withInput();
            }

            // Accept invitation for existing user
            $result = $this->invitationService->acceptInvitationForExistingUser($token, $existingUser);

            if (! $result['success']) {
                return back()->withErrors(['general' => $result['message']])->withInput();
            }

            // Log the user in
            Auth::login($existingUser);

            // Redirect to the workspace they were invited to join
            return $this->workspaceRedirectService->redirectToSpecificWorkspace($existingUser, $invitation->workspace)
                ->with('success', $result['message']);

        } else {
            // Handle new user registration
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            try {
                // Update user password
                $inviteeUser->update([
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ]);

                // Accept the invitation
                $result = $this->invitationService->acceptInvitation($token);

                if (! $result['success']) {
                    return back()->withErrors(['general' => $result['message']])->withInput();
                }

                // Log the user in
                Auth::login($inviteeUser);

                // Redirect to the workspace they were invited to join
                return $this->workspaceRedirectService->redirectToSpecificWorkspace($inviteeUser, $invitation->workspace)
                    ->with('success', "Welcome! You've successfully joined {$invitation->workspace->name}.");

            } catch (\Exception $e) {
                return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
            }
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
            'email' => 'This invitation link is invalid or has expired.',
        ]);
    }
}
