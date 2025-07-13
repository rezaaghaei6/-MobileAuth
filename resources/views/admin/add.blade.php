
@extends('layouts.app') {{-- قالب پنل ادمین شما --}}

@section('content')
    <h1>اضافه کردن ادمین جدید</h1>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.store') }}">
        @csrf

        <div>
            <label for="phone">شماره موبایل:</label><br>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="مثلاً ۹۱۲۳۴۵۶۷۸۹">
        </div>

        <div style="margin-top: 10px;">
            <label for="name">نام (اختیاری):</label><br>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
        </div>

        <div style="margin-top: 15px;">
            <button type="submit">اضافه کردن ادمین</button>
        </div>
    </form>
@endsection
