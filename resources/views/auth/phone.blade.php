@extends('layouts.app')

@section('content')
    <h1 style="margin-bottom: 20px;">ورود / ثبت نام</h1>

    {{-- پیام خطا --}}
    @if (session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    {{-- فرم ارسال شماره موبایل --}}
    <form method="POST" action="{{ route('send.code') }}" id="phone-form">
        @csrf

        {{-- شماره موبایل --}}
        <label for="phone" style="display: block; margin-bottom: 6px;">شماره موبایل:</label>
        <div style="display: flex; flex-direction: row-reverse; align-items: center; max-width: 280px; border: 1px solid #ccc; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
            <span style="padding: 10px 12px; background-color: #f0f0f0; font-weight: bold; border-left: 1px solid #ccc;">+98</span>
            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="9123456789"
                value="{{ old('phone') }}"
                style="flex: 1; padding: 10px; border: none; outline: none; direction: ltr; text-align: left; font-size: 16px;"
                required
                maxlength="11"
                autocomplete="off"
            >
        </div>
        @error('phone')
            <div style="color: red; margin-top: 5px;">{{ $message }}</div>
        @enderror

        {{-- نمایش شمارش معکوس اگر کاربر بلاک باشد --}}
        @if (session('blocked_until'))
            @php
                $blockedSeconds = session('blocked_until') - now()->timestamp;
            @endphp
            @if ($blockedSeconds > 0)
                <div style="color: orange; margin-top: 10px;" id="block-message">
                    لطفاً تا <span id="countdown">{{ gmdate('i:s', $blockedSeconds) }}</span> دیگر صبر کنید.
                </div>
                <script>
                    let remaining = {{ $blockedSeconds }};
                    const countdownEl = document.getElementById('countdown');

                    const interval = setInterval(() => {
                        remaining--;
                        if (remaining <= 0) {
                            clearInterval(interval);
                            document.getElementById('block-message').innerText = "محدودیت زمانی به پایان رسید. لطفاً فرم را دوباره ارسال کنید.";
                        } else {
                            const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                            const seconds = String(remaining % 60).padStart(2, '0');
                            countdownEl.innerText = minutes + ':' + seconds;
                        }
                    }, 1000);
                </script>
            @endif
        @endif

        {{-- کپچا --}}
        <div style="margin-top: 20px; display: flex; align-items: center;">
            <div id="captcha-img">{!! captcha_img() !!}</div>
            <button type="button" id="refresh-captcha"
                style="margin-right: 10px; font-size: 20px; cursor: pointer; background: none; border: none; padding: 5px;"
                title="تغییر تصویر">🔄</button>
        </div>

        <input type="text" name="captcha" placeholder="کد امنیتی" required
            style="margin-top: 10px; font-size: 16px; padding: 10px; width: 100%; max-width: 280px; box-sizing: border-box;">
        @error('captcha')
            <div style="color: red; margin-top: 5px;">{{ $message }}</div>
        @enderror

        {{-- دکمه ارسال --}}
        <button type="submit"
            style="margin-top: 20px; padding: 10px 20px; font-size: 16px; background-color: #3490dc; color: white; border: none; border-radius: 6px; cursor: pointer;">
            ارسال کد تایید
        </button>
    </form>

    {{-- اسکریپت‌ها --}}
    <script>
        // تبدیل اعداد فارسی به انگلیسی
        function toEnglishNumbers(str) {
            const persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            const english = ['0','1','2','3','4','5','6','7','8','9'];
            return str.replace(/[۰-۹]/g, w => english[persian.indexOf(w)]);
        }

        // فقط اعداد مجاز
        document.getElementById('phone').addEventListener('input', function(e) {
            let val = toEnglishNumbers(e.target.value);
            val = val.replace(/[^0-9]/g, '');
            if (val.length > 11) val = val.slice(0, 11);
            e.target.value = val;
        });

        // حذف صفر اول قبل از ارسال
        document.getElementById('phone-form').addEventListener('submit', function(e) {
            const input = document.getElementById('phone');
            let val = input.value;
            if (val.length === 11 && val.startsWith('0')) {
                input.value = val.slice(1);
            }
        });

        // رفرش کپچا
        document.getElementById('refresh-captcha').addEventListener('click', function () {
            fetch('{{ route('captcha.refresh') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-img').innerHTML = data.captcha;
                });
        });
    </script>
@endsection
