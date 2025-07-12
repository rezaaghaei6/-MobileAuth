<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
            'phone' => ['required', 'regex:/^9\d{9}$/'],
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.captcha' => 'کد امنیتی صحیح نیست.',
            'phone.regex' => 'شماره موبایل باید ۱۰ رقم و با 9 شروع شود.',
        ]);

        $phone = $request->phone;

        // بررسی محدودیت ارسال برای این شماره در دیتابیس (۱۰ دقیقه)
        $recentCode = VerificationCode::where('phone', $phone)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->latest()
            ->first();

        // بررسی محدودیت ارسال در مرورگر (session)
        $lastSent = session('last_code_sent_at');
        if ($recentCode || ($lastSent && now()->diffInMinutes(Carbon::parse($lastSent)) < 10)) {
            $blockedUntil = Carbon::parse($lastSent ?? $recentCode->created_at)->addMinutes(10);
            session(['blocked_until' => $blockedUntil->timestamp]);
            return back()->withErrors(['phone' => 'کد قبلاً ارسال شده است. لطفاً تا ۱۰ دقیقه دیگر مجدد تلاش کنید.']);
        }

        // تولید کد 6 رقمی
        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(2);  // اعتبار کد ۲ دقیقه

        VerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
            'is_expired' => false,
        ]);

        logger("کد تایید برای شماره $phone : $code");

        session([
            'phone' => $phone,
            'last_code_sent_at' => now(),
            'blocked_until' => now()->addMinutes(10)->timestamp,
        ]);

        return redirect()->route('verify.form')->with('success', 'کد تایید ارسال شد.');
    }

    public function showVerifyForm()
    {
        $phone = session('phone');
        if (!$phone) {
            return redirect()->route('phone.form')->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }
        return view('auth.verify', compact('phone'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/'],
            'code' => ['required', 'digits:6'],
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.captcha' => 'کد امنیتی صحیح نیست.',
            'phone.regex' => 'شماره موبایل باید ۱۰ رقم و با 9 شروع شود.',
            'code.digits' => 'کد تایید باید ۶ رقم باشد.',
        ]);

        $verification = VerificationCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('is_expired', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'کد تایید نامعتبر، منقضی یا استفاده شده است.']);
        }

        $verification->is_expired = true;
        $verification->save();

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            Auth::login($user);
            session()->forget(['phone', 'blocked_until', 'last_code_sent_at']);
            return redirect()->route('dashboard')->with('success', 'ورود با موفقیت انجام شد.');
        } else {
            session(['phone_to_register' => $request->phone]);
            return redirect()->route('register-name.form');
        }
    }

    public function showRegisterNameForm()
    {
        if (!session('phone_to_register')) {
            return redirect()->route('phone.form')->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }
        return view('auth.register-name');
    }

    public function registerName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $phone = session('phone_to_register');
        if (!$phone) {
            return redirect()->route('phone.form')->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }

        $user = User::create([
            'phone' => $phone,
            'name' => $request->name,
        ]);

        Auth::login($user);
        session()->forget(['phone_to_register', 'phone', 'blocked_until', 'last_code_sent_at']);

        return redirect()->route('dashboard')->with('success', 'ثبت نام با موفقیت انجام شد.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('phone.form');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
