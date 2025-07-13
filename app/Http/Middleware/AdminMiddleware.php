<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // بررسی اینکه کاربر لاگین کرده باشد و نقش admin داشته باشد
        if (!$user || $user->role !== 'admin') {
            abort(403, 'شما به این بخش دسترسی ندارید.');
        }

        return $next($request);
    }
}