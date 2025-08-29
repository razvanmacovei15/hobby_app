<?php

// Add these routes to routes/web.php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\EmailTrackingController;

// Invitation routes
Route::get('/invitation/accept/{token}', [InvitationController::class, 'acceptInvitation'])
    ->name('workspace.invitation.accept');

Route::get('/register/invitation/{token}', [InvitationController::class, 'showRegistrationForm'])
    ->name('register.from-invitation');

Route::post('/register/invitation/{token}', [InvitationController::class, 'registerFromInvitation'])
    ->name('register.process-invitation');

// Email tracking routes
Route::get('/email/track/open/{id}', [EmailTrackingController::class, 'trackOpen'])
    ->name('email.track.open');

Route::get('/email/track/click/{id}', [EmailTrackingController::class, 'trackClick'])
    ->name('email.track.click');
