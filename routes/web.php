<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Mews\Captcha\Facades\Captcha;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showPhoneForm'])->name('phone.form');

Route::post('/send-code', [AuthController::class, 'sendCode'])->name('send.code');

Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.form');

Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify.code');

Route::get('/register-name', [AuthController::class, 'showRegisterNameForm'])->name('register-name.form');
Route::post('/register-name', [AuthController::class, 'registerName'])->name('register-name.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/login', [AuthController::class, 'showPhoneForm'])->name('login');

Route::get('/captcha-refresh', function () {
    return response()->json(['captcha' => Captcha::img()]);
})->name('captcha.refresh');
