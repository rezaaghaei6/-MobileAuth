@extends('layouts.app')

@section('content')
    <h2>لاگ کپچاها (جمع: {{ $count }})</h2>
    <table>
        <thead>
            <tr>
                <th>شماره</th>
                <th>وضعیت</th>
                <th>آی‌پی</th>
                <th>توضیح</th>
                <th>تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->phone ?? '—' }}</td>
                    <td>{{ $log->status }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ jdate($log->created_at)->format('Y/m/d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
@endsection
