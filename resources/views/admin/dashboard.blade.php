@extends('layouts.app')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="{{ route('admin.users') }}" class="block p-6 bg-white rounded shadow hover:bg-gray-50">
        <h3 class="text-xl font-bold text-gray-800">Manage Users</h3>
        <p class="text-gray-500">View registered users details.</p>
    </a>
    
    <a href="{{ route('admin.sports.index') }}" class="block p-6 bg-white rounded shadow hover:bg-gray-50">
        <h3 class="text-xl font-bold text-gray-800">Manage Sports</h3>
        <p class="text-gray-500">Add or remove sport categories.</p>
    </a>

    <a href="{{ route('admin.activity') }}" class="block p-6 bg-white rounded shadow hover:bg-gray-50">
        <h3 class="text-xl font-bold text-gray-800">Activity Logs</h3>
        <p class="text-gray-500">Audit system actions and history.</p>
    </a>
</div>
@endsection