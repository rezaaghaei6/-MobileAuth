@extends('layouts.app')

@section('content')
<h1>ثبت نام</h1>

@if (session('error'))
    <div class="error" style="color: red;">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('register-name.submit') }}">
    @csrf

    <label for="name">نام کامل:</label>
    <input type="text" name="name" placeholder="مثلاً علی احمدی" required>
    @error('name')
        <div class="error" style="color: red;">{{ $message }}</div>
    @enderror

    <button type="submit" style="margin-top: 10px;">ثبت نام و ورود</button>
</form>
@endsection
