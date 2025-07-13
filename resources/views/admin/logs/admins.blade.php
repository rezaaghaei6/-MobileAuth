@extends('layouts.app')

@section('content')
    <h2>لاگ ادمین‌ها (تعداد: {{ $count }})</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px;">
        <thead>
            <tr>
                <th>نام ادمین</th>
                <th>شماره موبایل</th>
                <th>زمان ورود</th>
                <th>زمان خروج</th>
                <th>زمان ثبت نام</th>
                <th>آی‌پی</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->admin->name ?? 'نامشخص' }}</td>
                <td>{{ $log->admin->phone ?? 'نامشخص' }}</td>
                <td>{{ $log->login_at }}</td>
                <td>{{ $log->logout_at ?? '-' }}</td>
                <td>{{ $log->admin->created_at ?? '-' }}</td>
                <td>{{ $log->ip_address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}
@endsection
