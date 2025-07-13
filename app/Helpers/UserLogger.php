<?php
namespace App\Helpers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;

class UserLogger
{
    public static function log($action, $message = null)
    {
        UserLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'message' => $message,
            'ip' => request()->ip(),
        ]);
    }
}

