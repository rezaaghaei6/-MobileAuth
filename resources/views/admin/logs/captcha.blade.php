@extends('layouts.app')

@section('content')
    <h2>لاگ ریکپچا (تعداد: {{ $count }})</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>نام کاربر</th>
                <th>شماره موبایل</th>
                <th>زمان ورود</th>
                <th>وضعیت ریکپچا</th>
                <th>آی‌پی</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->user->name ?? 'نامشخص' }}</td>
                <td>{{ $log->user->phone ?? 'نامشخص' }}</td>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->status }}</td>
                <td>{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}
@endsection
