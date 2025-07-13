@extends('layouts.app')

@section('content')
    <h2>اضافه کردن ادمین جدید</h2>

    @if(session('success'))
        <div style="color: green; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.store') }}">
        @csrf
        <div>
            <label>شماره موبایل (بدون صفر اول):</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="10" pattern="9[0-9]{9}">
            @error('phone') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>نام:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>رمز عبور:</label>
            <input type="password" name="password" required minlength="6">
            @error('password') <div style="color: red;">{{ $message }}</div> @enderror
        </div>

        <div>
            <label>تکرار رمز عبور:</label>
            <input type="password" name="password_confirmation" required minlength="6">
        </div>

        <button type="submit">اضافه کردن</button>
    </form>
@endsection
