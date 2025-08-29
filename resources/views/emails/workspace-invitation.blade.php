<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Invitation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .workspace-info {
            background: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .cta-button {
            display: inline-block;
            background: #4299e1;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.2s;
        }
        .cta-button:hover {
            background: #3182ce;
        }
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            font-size: 14px;
            color: #718096;
            text-align: center;
        }
        .expiry-notice {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
        }
        .tracking-pixel {
            width: 1px;
            height: 1px;
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        {{-- Add your logo here --}}
        {{-- <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logo"> --}}
        <h1 class="title">You're Invited!</h1>
    </div>

    <div class="content">
        <p>Hi {{ $invitee->first_name }},</p>

        <p><strong>{{ $invitedBy->first_name }} {{ $invitedBy->last_name }}</strong> has invited you to join the <strong>{{ $workspace->name }}</strong> workspace.</p>

        <div class="workspace-info">
            <h3 style="margin-top: 0;">Workspace Details</h3>
            <p><strong>Name:</strong> {{ $workspace->name }}</p>
            @if($workspace->description)
                <p><strong>Description:</strong> {{ $workspace->description }}</p>
            @endif
            <p><strong>Invited by:</strong> {{ $invitedBy->first_name }} {{ $invitedBy->last_name }}</p>
        </div>

        <p>Click the button below to accept your invitation and join the workspace:</p>

        <div style="text-align: center;">
            <a href="{{ $acceptUrl }}" class="cta-button" style="color: white;">
                Accept Invitation
            </a>
        </div>

        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #4299e1;">{{ $acceptUrl }}</p>

        <div class="expiry-notice">
            <strong>⚠️ Important:</strong> This invitation will expire on {{ $expiresAt->format('F j, Y \a\t g:i A') }}.
        </div>

        <p>If you have any questions or need help, please contact {{ $invitedBy->first_name }} at {{ $invitedBy->email }}.</p>
    </div>

    <div class="footer">
        <p>This invitation was sent to {{ $invitee->email }}.</p>
        <p>If you weren't expecting this invitation, you can safely ignore this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

{{-- Tracking pixel for open tracking --}}
<img src="{{ $trackingPixelUrl }}" alt="" class="tracking-pixel" width="1" height="1">
</body>
</html>
