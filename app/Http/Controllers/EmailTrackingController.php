<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailTrackingController extends Controller
{
    public function trackOpen($id)
    {
        $emailLog = EmailLog::find($id);

        if ($emailLog && !$emailLog->opened_at) {
            $emailLog->update([
                'opened_at' => now(),
                'status' => 'opened'
            ]);
        }

        // Return a 1x1 transparent pixel
        return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'))
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function trackClick($id)
    {
        $emailLog = EmailLog::find($id);

        if ($emailLog && !$emailLog->clicked_at) {
            $emailLog->update([
                'clicked_at' => now(),
                'status' => 'clicked'
            ]);
        }

        return response()->json(['status' => 'tracked']);
    }
}
