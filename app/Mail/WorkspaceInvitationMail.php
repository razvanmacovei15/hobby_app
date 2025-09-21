<?php

// app/Mail/WorkspaceInvitationMail.php
namespace App\Mail;

use App\Models\WorkspaceInvitation;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class WorkspaceInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public WorkspaceInvitation $invitation;
    public EmailLog $emailLog;

    public function __construct(WorkspaceInvitation $invitation, EmailLog $emailLog)
    {
        $this->invitation = $invitation;
        $this->emailLog = $emailLog;
    }

    public function build()
    {
        return $this->from('noreply@macocoding.com', 'MacoCoding Team')
            ->subject("You're invited to join {$this->invitation->workspace->name}")
            ->view('emails.workspace-invitation')
            ->with([
                'invitation' => $this->invitation,
                'workspace' => $this->invitation->workspace,
                'invitee' => $this->invitation->invitee,
                'invitedBy' => $this->invitation->invitedBy,
                'acceptUrl' => $this->invitation->getInvitationUrl(),
                'registerUrl' => route('register.from-invitation', $this->invitation->token),
                'expiresAt' => $this->invitation->expires_at,
                'trackingPixelUrl' => $this->getTrackingPixelUrl(),
                'trackingId' => $this->emailLog->id,
            ])
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()
                    ->addTextHeader('X-Email-Log-ID', $this->emailLog->id)
                    ->addTextHeader('X-Invitation-Token', $this->invitation->token)
                    ->addTextHeader('X-Mailer', 'MacoCoding Platform');
            });
    }

    private function getTrackingPixelUrl(): string
    {
        return route('email.track.open', ['id' => $this->emailLog->id]);
    }
}
