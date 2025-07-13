<?php
namespace App\Helpers;

use App\Models\CaptchaLog;
use Illuminate\Support\Facades\Auth;

class CaptchaLogger
{
    public static function log($success, $details = null)
    {
        CaptchaLog::create([
            'user_id' => Auth::id(),
            'success' => $success,
            'ip' => request()->ip(),
            'details' => $details,
        ]);
    }
}

