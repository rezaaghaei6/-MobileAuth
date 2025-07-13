@extends('layouts.app')

@section('content')
    <h2>سلام {{ auth()->user()->name }}</h2>
    <p>شما وارد حساب شدید.</p>

    @if(auth()->user()->role === 'admin')
        <div style="margin-top: 30px;">
            <a href="{{ route('admin.logs.users') }}" class="btn btn-primary" style="margin-bottom: 10px; display: inline-block;">لاگ کاربران عادی</a>
            <a href="{{ route('admin.logs.captcha') }}" class="btn btn-warning" style="margin-bottom: 10px; display: inline-block;">لاگ ریکپچا</a>
            <a href="{{ route('admin.add') }}" class="btn btn-success" style="margin-bottom: 10px; display: inline-block;">اضافه کردن ادمین جدید</a>
        </div>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">خروج</button>
    </form>
@endsection
