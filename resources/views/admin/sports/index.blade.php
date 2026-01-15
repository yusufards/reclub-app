@extends('layouts.app')
@section('content')
<div class="flex gap-6 flex-col md:flex-row">
    <div class="flex-1 bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Sports List</h2>
        <ul>
            @foreach($sports as $s)
            <li class="flex justify-between items-center border-b py-2">
                <span>{{ $s->name }}</span>
                <form action="{{ route('admin.sports.destroy', $s) }}" method="POST" onsubmit="return confirm('Delete?');">
                    @csrf @method('DELETE')
                    <button class="text-red-500 text-sm">Delete</button>
                </form>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $sports->links() }}</div>
    </div>

    <div class="w-full md:w-1/3 bg-white p-6 rounded shadow h-fit">
        <h2 class="text-xl font-bold mb-4">Add Sport</h2>
        <form action="{{ route('admin.sports.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm mb-1">Name</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-1">Description</label>
                <textarea name="description" class="w-full border p-2 rounded"></textarea>
            </div>
            <button class="bg-blue-600 text-white w-full py-2 rounded">Save</button>
        </form>
    </div>
</div>
@endsection