@extends('layouts.app')

@section('content')

@if (session('error'))
    <div class="error" style="color: red;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('verify.code') }}">
    @csrf

    <input type="hidden" name="phone" value="{{ $phone }}">

    <input type="text" name="code" placeholder="کد 6 رقمی" required>
    @error('code')
        <div class="error" style="color: red;">{{ $message }}</div>
    @enderror

    <div style="margin-top: 10px;">
        <label for="captcha">کد امنیتی:</label><br>
        <img src="{{ captcha_src() }}" alt="captcha" id="captcha-img" style="vertical-align: middle; cursor:pointer;" title="برای تغییر تصویر کلیک کنید">
        <a href="javascript:void(0);" onclick="refreshCaptcha()" style="font-size: 12px; margin-left: 8px; text-decoration: underline; cursor: pointer;">تغییر تصویر</a><br>
        <input type="text" name="captcha" placeholder="کد امنیتی را وارد کنید" required>
        @error('captcha')
            <div class="error" style="color: red;">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" style="margin-top: 10px;">تایید</button>
</form>

<script>
    function refreshCaptcha() {
        let captcha = document.getElementById('captcha-img');
        captcha.src = '{{ captcha_src() }}' + '?' + Date.now();
    }
    document.getElementById('captcha-img').addEventListener('click', refreshCaptcha);
</script>

@endsection
