<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkspaceInvitation;
use App\Services\IWorkspaceInvitationService;
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

    public function showRegistrationForm(string $token)
    {
        $invitation = WorkspaceInvitation::findByToken($token);

        if (!$invitation) {
            return view('auth.invitation-invalid', [
                'message' => 'This invitation link is invalid or has expired.'
            ]);
        }

        if ($invitation->isExpired()) {
            return view('auth.invitation-invalid', [
                'message' => 'This invitation has expired. Please contact the person who invited you for a new invitation.'
            ]);
        }

        if ($invitation->isAccepted()) {
            return view('auth.invitation-invalid', [
                'message' => 'This invitation has already been used.'
            ]);
        }

        $invitation->load(['workspace', 'invitee', 'invitedBy']);

        return view('auth.register-from-invitation', [
            'invitation' => $invitation,
            'token' => $token,
            'prefilledData' => [
                'first_name' => $invitation->invitee->first_name ?? 'User',
                'last_name' => $invitation->invitee->last_name ?? 'Test',
                'email' => $invitation->invitee->email,
            ]
        ]);
    }

    public function registerFromInvitation(Request $request, string $token)
    {
        $invitation = WorkspaceInvitation::findByToken($token);

        if (!$invitation || $invitation->isExpired() || $invitation->isAccepted()) {
            return back()->withErrors(['token' => 'Invalid or expired invitation.']);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create the user account
            $user = User::update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Accept the invitation
            $result = $this->invitationService->acceptInvitation($token);

            if (!$result['success']) {
                $user->delete();
                return back()->withErrors(['general' => $result['message']])->withInput();
            }

            // Log the user in
            Auth::login($user);

            //todo must link the user to workspace

            //todo must link user role to this workspace

            return redirect()->route('filament.admin.pages.dashboard')
                ->with('success', "Welcome! You've successfully joined {$invitation->workspace->name}.");

        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function acceptInvitation(string $token)
    {
        // Redirect to the registration form
        return redirect()->route('register.from-invitation', $token);
    }
}
