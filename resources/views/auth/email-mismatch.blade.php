<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Mismatch - {{ $invitation->workspace->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 rounded-full bg-yellow-100 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Email Address Mismatch
                </h2>
                <p class="text-sm text-gray-600">
                    You're currently logged in with a different email address
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200">

                <!-- Mismatch Info Card -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 mb-2">
                                Email Address Conflict
                            </h3>
                            <div class="text-sm text-yellow-700 space-y-1">
                                <p><strong>You're logged in as:</strong> {{ $currentUserEmail }}</p>
                                <p><strong>This invitation is for:</strong> {{ $invitedEmail }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workspace Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                You've been invited to join <strong>{{ $invitation->workspace->name }}</strong>
                                by {{ $invitation->invitedBy->first_name }} {{ $invitation->invitedBy->last_name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Options -->
                <div class="space-y-4">
                    <!-- Option 1: Logout and continue with invited email -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                            Option 1: Use the invited email address
                        </h4>
                        <p class="text-sm text-gray-600 mb-3">
                            Log out and continue with the email address this invitation was sent to.
                        </p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ route('register.from-invitation', $token) }}">
                            <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Logout and Continue with {{ $invitedEmail }}
                            </button>
                        </form>
                    </div>

                    <!-- Option 2: Contact the person who invited them -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                            Option 2: Request a new invitation
                        </h4>
                        <p class="text-sm text-gray-600 mb-3">
                            Contact {{ $invitation->invitedBy->first_name }} {{ $invitation->invitedBy->last_name }} 
                            to send a new invitation to {{ $currentUserEmail }}.
                        </p>
                        <a href="mailto:{{ $invitation->invitedBy->email }}?subject=New%20Invitation%20Request&body=Hi%20{{ $invitation->invitedBy->first_name }},%0D%0A%0D%0AI%20received%20an%20invitation%20to%20join%20{{ urlencode($invitation->workspace->name) }}%20but%20it%20was%20sent%20to%20{{ urlencode($invitedEmail) }}.%20Could%20you%20please%20send%20a%20new%20invitation%20to%20{{ urlencode($currentUserEmail) }}?%0D%0A%0D%0AThanks!"
                           class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send Email Request
                        </a>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Need help? Contact your system administrator.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>