<?php

namespace App\Http\Controllers;

use App\Models\Permission\Role;
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

        $invitation->load(['workspace', 'invitedBy']);

        // Get user directly by ID since morphTo relationship has issues
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
        $invitation = WorkspaceInvitation::findByToken($token);

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            return back()->withErrors(['token' => 'Invalid or expired invitation.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Get the user directly by ID since morphTo isn't working
            $user = User::findOrFail($invitation->invitee_id);
            // Update user password
            $user->update([
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
            $user->save();

            // Accept the invitation
            $result = $this->invitationService->acceptInvitation($token);

            if (!$result['success']) {
                return back()->withErrors(['general' => $result['message']])->withInput();
            }
            // Log the user in
            Auth::login($user);

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
