<?php

namespace App\Services\Implementations;

use App\Mail\WorkspaceInvitationMail;
use App\Models\EmailLog;
use App\Models\Permission\Role;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Services\IWorkspaceInvitationService;
use App\Services\IWorkspaceUserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WorkspaceInvitationService implements IWorkspaceInvitationService
{
    private IWorkspaceUserService $workspaceUserService;

    public function __construct(IWorkspaceUserService $workspaceUserService)
    {
        $this->workspaceUserService = $workspaceUserService;
    }

    public function inviteEmployee(int $employeeId, int $workspaceId, array $roleIds): WorkspaceInvitation
    {
        DB::beginTransaction();

        try {
            // Get the employee (User model)
            $employee = User::findOrFail($employeeId);
            $workspace = Workspace::findOrFail($workspaceId);
            $invitedBy = Auth::user();

            // Check if there's already a pending invitation
            $existingInvitation = WorkspaceInvitation::where('workspace_id', $workspaceId)
                ->where('invitee_id', $employeeId)
                ->where('invitee_type', User::class)
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->first();

            if ($existingInvitation) {
                throw new Exception('A pending invitation already exists for this employee.');
            }

            // Create the invitation
            $invitation = WorkspaceInvitation::createForUser(
                $employee,
                $workspace,
                $invitedBy,
                $roleIds
            );

            // Send the email
            $this->sendInvitationEmail($invitation);

            DB::commit();

            return $invitation;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function sendInvitationEmail(WorkspaceInvitation $invitation): bool
    {
        try {
            $invitation->load(['workspace', 'invitee', 'invitedBy']);

            // Create email log entry
            $emailLog = EmailLog::create([
                'workspace_invitation_id' => $invitation->id,
                'recipient_email' => $invitation->invitee->email,
                'recipient_name' => $invitation->invitee->first_name.' '.$invitation->invitee->last_name,
                'sender_email' => config('mail.from.address'),
                'sender_name' => config('mail.from.name'),
                'subject' => "You're invited to join {$invitation->workspace->name}",
                'status' => 'pending',
                'sent_at' => now(),
            ]);

            // Send the email
            Mail::to($invitation->invitee->email)
                ->send(new WorkspaceInvitationMail($invitation, $emailLog));

            // Update email log status
            $emailLog->update(['status' => 'sent']);

            return true;

        } catch (Exception $e) {
            // Log the error and update email status
            if (isset($emailLog)) {
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            \Log::error('Failed to send workspace invitation email', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function acceptInvitation(string $token): array
    {
        $invitation = $this->getInvitationByToken($token);
        if (! $invitation) {
            return [
                'success' => false,
                'message' => 'Invalid or expired invitation.',
            ];
        }

        if ($invitation->isAccepted()) {
            return [
                'success' => false,
                'message' => 'This invitation has already been accepted.',
            ];
        }

        if ($invitation->isExpired()) {
            return [
                'success' => false,
                'message' => 'This invitation has expired.',
            ];
        }

        DB::beginTransaction();

        try {
            // Accept the invitation
            $invitation->accept();

            // Add the user to the workspace with the specified roles
            $workspace = $invitation->workspace;
            $user = User::findOrFail($invitation->invitee_id);

            // Check if this is the user's first workspace
            $isFirstWorkspace = $user->workspaces()->count() === 0;

            // Attach user to workspace, making it default if it's their first
            $user->workspaces()->attach($invitation->workspace_id, [
                'is_default' => $isFirstWorkspace,
            ]);

            $roles = Role::query()->whereIn('id', $invitation->roles)
                ->where('workspace_id', $invitation->workspace_id)
                ->get();

            foreach ($roles as $role) {
                $user->assignRole($role);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Invitation accepted successfully!',
                'workspace' => $workspace,
                'user' => $user,
            ];

        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to accept invitation. Please try again.',
            ];
        }
    }

    public function getInvitationByToken(string $token): ?WorkspaceInvitation
    {
        return WorkspaceInvitation::findByToken($token);
    }

    public function resendInvitation(int $invitationId): bool
    {
        $invitation = WorkspaceInvitation::findOrFail($invitationId);

        if ($invitation->isAccepted()) {
            throw new Exception('Cannot resend an accepted invitation.');
        }

        // Update expiration date
        $invitation->update(['expires_at' => now()->addDays(2)]);

        return $this->sendInvitationEmail($invitation);
    }

    public function cancelInvitation(int $invitationId): bool
    {
        $invitation = WorkspaceInvitation::findOrFail($invitationId);

        if ($invitation->isAccepted()) {
            throw new Exception('Cannot cancel an accepted invitation.');
        }

        // Soft delete or mark as cancelled
        return $invitation->delete();
    }

    public function acceptInvitationForExistingUser(string $token, User $user): array
    {
        $invitation = $this->getInvitationByToken($token);
        if (! $invitation) {
            return [
                'success' => false,
                'message' => 'Invalid or expired invitation.',
            ];
        }

        if ($invitation->isAccepted()) {
            return [
                'success' => false,
                'message' => 'This invitation has already been accepted.',
            ];
        }

        if ($invitation->isExpired()) {
            return [
                'success' => false,
                'message' => 'This invitation has expired.',
            ];
        }

        // Verify the user email matches the invitation
        if ($user->email !== $invitation->invitee->email) {
            return [
                'success' => false,
                'message' => 'User email does not match the invitation.',
            ];
        }

        // Check if user is already in the workspace
        if ($this->workspaceUserService->isUserInWorkspace($user, $invitation->workspace)) {
            return [
                'success' => false,
                'message' => 'You are already a member of this workspace.',
            ];
        }

        DB::beginTransaction();

        try {
            // Accept the invitation
            $invitation->accept();

            // Add user to workspace using the service
            $roleIds = $invitation->roles;
            $this->workspaceUserService->addUserToWorkspace($user, $invitation->workspace, $roleIds);

            // Assign roles
            $roles = Role::query()->whereIn('id', $roleIds)
                ->where('workspace_id', $invitation->workspace_id)
                ->get();

            foreach ($roles as $role) {
                $user->assignRole($role);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Welcome back! You\'ve been added to the workspace.',
                'workspace' => $invitation->workspace,
                'user' => $user,
            ];

        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to accept invitation. Please try again.',
            ];
        }
    }
}
