@extends('layouts.app')

@section('content')
    <h2>سلام {{ auth()->user()->name }}</h2>
    <p>شما وارد حساب شدید.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">خروج</button>
    </form>
@endsection
