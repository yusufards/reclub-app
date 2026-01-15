@extends('layouts.app')
@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">User List</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="w-full bg-gray-100 text-left">
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-b">
                    <td class="p-3">{{ $u->id }}</td>
                    <td class="p-3">{{ $u->name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs {{ $u->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-gray-100' }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="p-3">{{ $u->phone ?? '-' }}</td>
                    <td class="p-3">{{ $u->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection