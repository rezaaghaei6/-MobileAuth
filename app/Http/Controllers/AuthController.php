<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Helpers\UserLogger;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showPhoneForm()
    {
        return view('auth.phone');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^9\d{9}$/',
            'captcha' => 'required|captcha',
        ]);

        $phone = ltrim($request->phone, '0');

        $recentCode = VerificationCode::where('phone', $phone)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->first();

        if ($recentCode) {
            $remainingSeconds = $recentCode->created_at->addMinutes(10)->diffInSeconds(now());
            $minutes = floor($remainingSeconds / 60);
            $seconds = $remainingSeconds % 60;

            // لاگ تلاش برای ارسال کد در فاصله محدود
            UserLogger::log('send_code_blocked', "ارسال مجدد کد برای شماره $phone محدود شد.");

            return back()->withErrors([
                'phone' => "برای این شماره قبلاً کد ارسال شده است. لطفاً {$minutes} دقیقه و {$seconds} ثانیه دیگر صبر کنید."
            ])->withInput();
        }

        $code = rand(100000, 999999);

        VerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
            'ip_address' => $request->ip(),
        ]);

        session(['verify_phone' => $phone]);

        // ✅ لاگ ارسال کد تأیید
        UserLogger::log('send_code', "کد $code برای شماره $phone ارسال شد.");

        return redirect()->route('verify.form')->with('success', 'کد تأیید ارسال شد.');
    }

    public function showVerifyForm()
    {
        $phone = session('verify_phone');
        if (!$phone) {
            return redirect()->route('phone.form')->with('error', 'ابتدا شماره موبایل را وارد کنید.');
        }
        return view('auth.verify', compact('phone'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^9\d{9}$/',
            'code' => 'required|digits:6',
            'captcha' => 'required|captcha',
        ]);

        $verification = VerificationCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('is_expired', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            // ✅ لاگ تلاش ناموفق تأیید کد
            UserLogger::log('verify_failed', "کد اشتباه یا منقضی برای شماره {$request->phone}");
            return back()->withErrors(['code' => 'کد تایید نامعتبر، منقضی یا استفاده شده است.'])->withInput();
        }

        $verification->update(['is_expired' => true]);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => 'بدون‌نام']
        );

        Auth::login($user);
        session()->forget(['verify_phone']);

        // ✅ لاگ ورود موفق
        UserLogger::log('login', "ورود موفق کاربر با شماره: {$user->phone}");

        if ($user->name === 'بدون‌نام') {
            session(['phone_to_register' => $user->phone]);
            return redirect()->route('register-name.form');
        }

        return redirect()->route('dashboard')->with('success', 'ورود موفقیت‌آمیز بود.');
    }

    public function showRegisterNameForm()
    {
        if (!session('phone_to_register')) {
            return redirect()->route('phone.form')->with('error', 'ابتدا شماره موبایل را وارد کنید.');
        }

        return view('auth.register-name');
    }

    public function registerName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $phone = session('phone_to_register');
        if (!$phone) {
            return redirect()->route('phone.form')->with('error', 'شماره در سشن نیست.');
        }

        $user = User::where('phone', $phone)->first();
        if ($user) {
            $user->name = $request->name;
            $user->save();

            Auth::login($user);
            session()->forget(['phone_to_register', 'verify_phone']);

            // ✅ لاگ ثبت‌نام نام
            UserLogger::log('register_name', "ثبت نام با نام: {$request->name} و شماره: {$user->phone}");

            return redirect()->route('dashboard')->with('success', 'ثبت نام کامل شد.');
        }

        return redirect()->route('phone.form')->with('error', 'کاربر یافت نشد.');
    }

    public function logout()
    {
        if (Auth::check()) {
            // ✅ لاگ خروج
            UserLogger::log('logout', 'خروج کاربر با شماره: ' . Auth::user()->phone);
        }

        Auth::logout();
        return redirect()->route('phone.form');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
