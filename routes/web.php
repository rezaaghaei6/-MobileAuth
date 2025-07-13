<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\AdminUserController;
use Mews\Captcha\Facades\Captcha;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// صفحات احراز هویت
Route::get('/', [AuthController::class, 'showPhoneForm'])->name('phone.form');
Route::get('/login', [AuthController::class, 'showPhoneForm'])->name('login');
Route::post('/send-code', [AuthController::class, 'sendCode'])->name('send.code');
Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify.code');
Route::get('/register-name', [AuthController::class, 'showRegisterNameForm'])->name('register-name.form');
Route::post('/register-name', [AuthController::class, 'registerName'])->name('register-name.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// داشبورد کاربر
Route::get('/dashboard', fn() => view('dashboard'))->middleware('auth')->name('dashboard');

// رفرش کپچا
Route::get('/captcha-refresh', fn() => response()->json(['captcha' => Captcha::img()]))->name('captcha.refresh');

// ------------------------------------------------------------------
// مسیرهای ادمین با middleware 'auth' و 'admin'
// ------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/logs/users', [AdminLogController::class, 'userLogs'])->name('logs.users');
    Route::get('/logs/admins', [AdminLogController::class, 'adminLogs'])->name('logs.admins');
    Route::get('/logs/captcha', [AdminLogController::class, 'captchaLogs'])->name('logs.captcha');

    Route::get('/add', [AdminUserController::class, 'create'])->name('add');
    Route::post('/add', [AdminUserController::class, 'store'])->name('store');
});
