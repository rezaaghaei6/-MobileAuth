@extends('layouts.app')

@section('content')
    <h2 class="mb-4">تمام لاگ‌های کاربران (تعداد: {{ $count }})</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>کاربر</th>
                <th>نوع</th>
                <th>پیام</th>
                <th>IP</th>
                <th>مرورگر</th>
                <th>زمان</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>
                        {{ $log->user?->name ?? 'سیستم' }}
                        <br>
                        <small>{{ $log->user?->phone }}</small>
                    </td>
                    <td>{{ $log->type }}</td>
                    <td>{{ $log->message }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td style="max-width: 300px;">{{ \Illuminate\Support\Str::limit($log->user_agent, 100) }}</td>
                    <td>{{ $log->created_at->translatedFormat('Y/m/d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@endsection
