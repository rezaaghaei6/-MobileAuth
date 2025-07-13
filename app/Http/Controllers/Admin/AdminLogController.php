<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLog;
use App\Models\AdminLog;
use App\Models\CaptchaLog;

class AdminLogController extends Controller
{
    /**
     * نمایش لاگ کاربران عادی
     */
    public function userLogs()
    {
        $logs = UserLog::with('user')->latest()->paginate(20);
        $count = UserLog::count();

        return view('admin.logs.users', compact('logs', 'count'));
    }

    /**
     * نمایش لاگ ادمین‌ها
     */
    public function adminLogs()
    {
        $logs = AdminLog::with('admin')->latest()->paginate(20);
        $count = AdminLog::count();

        return view('admin.logs.admins', compact('logs', 'count'));
    }

    /**
     * نمایش لاگ کپچاها
     */
    public function captchaLogs()
    {
        $logs = CaptchaLog::with('user')->latest()->paginate(20);
        $count = CaptchaLog::count();

        return view('admin.logs.captcha', compact('logs', 'count'));
    }
}
