<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    // نمایش فرم ورود شماره موبایل
    public function showPhoneForm()
    {
        return view('auth.phone');
    }

    // ارسال کد تایید به شماره موبایل
    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/'],  // شماره باید ۱۰ رقم و با 9 شروع شود
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.captcha' => 'کد امنیتی صحیح نیست.',
            'phone.regex' => 'شماره موبایل باید ۱۰ رقم و با 9 شروع شود.',
        ]);

        $phone = $request->phone;

        // تولید کد 6 رقمی
        $code = rand(100000, 999999);

        $expiresAt = Carbon::now()->addMinutes(2);  // اعتبار کد 2 دقیقه

        VerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
            'is_expired' => false,
        ]);

        // ارسال پیامک واقعی اینجا انجام شود
        logger("کد تأیید برای شماره $phone : $code");

        session(['phone' => $phone]);

        return redirect()->route('verify.form')->with('success', 'کد تایید ارسال شد.');
    }

    // نمایش فرم وارد کردن کد تایید
    public function showVerifyForm()
    {
        $phone = session('phone');
        if (!$phone) {
            return redirect()->route('phone.form')->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }
        return view('auth.verify', compact('phone'));
    }

    // بررسی کد تایید و لاگین کاربر
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/'],  // شماره ۱۰ رقمی با 9
            'code' => ['required', 'digits:6'],          // کد 6 رقمی
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

        // کد را منقضی کن
        $verification->is_expired = true;
        $verification->save();

        // چک کن کاربر وجود داره یا نه
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            // اگر هست، لاگین کن و بفرست داشبورد
            Auth::login($user);
            session()->forget('phone');
            return redirect()->route('dashboard')->with('success', 'ورود با موفقیت انجام شد.');
        } else {
            // اگر نیست، شماره رو ذخیره کن برای ثبت نام و بفرست فرم نام
            session(['phone_to_register' => $request->phone]);
            return redirect()->route('register-name.form');
        }
    }

    // نمایش فرم وارد کردن نام بعد از تایید کد برای کاربر جدید
    public function showRegisterNameForm()
    {
        if (!session('phone_to_register')) {
            return redirect()->route('phone.form')->with('error', 'لطفا ابتدا شماره موبایل خود را وارد کنید.');
        }
        return view('auth.register-name');
    }

    // ذخیره نام و ایجاد کاربر جدید
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
        session()->forget('phone_to_register');
        session()->forget('phone');

        return redirect()->route('dashboard')->with('success', 'ثبت نام با موفقیت انجام شد.');
    }

    // خروج از حساب کاربری
    public function logout()
    {
        Auth::logout();
        return redirect()->route('phone.form');
    }

    // رفرش کردن کپچا
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}
