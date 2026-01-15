@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Activity Logs</h2>
        
        <form method="GET" class="flex gap-2">
            <select name="action" class="border p-2 rounded text-sm">
                <option value="">All Actions</option>
                <option value="room_created" {{ request('action')=='room_created'?'selected':'' }}>Room Created</option>
                <option value="join_requested" {{ request('action')=='join_requested'?'selected':'' }}>Join Requested</option>
                <option value="join_confirmed" {{ request('action')=='join_confirmed'?'selected':'' }}>Join Confirmed</option>
                <option value="login_email" {{ request('action')=='login_email'?'selected':'' }}>Login</option>
            </select>
            <button class="bg-gray-800 text-white px-3 py-1 rounded text-sm">Filter</button>
        </form>
    </div>

    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Time</th>
                <th class="p-3 text-left">Actor</th>
                <th class="p-3 text-left">Action</th>
                <th class="p-3 text-left">Subject ID</th>
                <th class="p-3 text-left">Meta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr class="border-b">
                <td class="p-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td class="p-3 font-medium">{{ $log->actor->name ?? 'System' }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded bg-blue-50 text-blue-700">{{ $log->action }}</span>
                </td>
                <td class="p-3">{{ $log->subject_id ?? '-' }}</td>
                <td class="p-3 text-gray-500">
                    @if($log->meta)
                        <pre class="text-xs">{{ json_encode($log->meta) }}</pre>
                    @else - @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $logs->withQueryString()->links() }}</div>
</div>
@endsection