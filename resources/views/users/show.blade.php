@extends('layouts.app')

@section('title', 'User Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">User Detail</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="col-span-2">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Basic Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">ID</label>
                    <p class="mt-1 text-gray-900">{{ $user->id }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Role</label>
                    <p class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>
                </div>

                <!-- Timestamps -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Timestamps</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Updated At</label>
                    <p class="mt-1 text-gray-900">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Last Login</label>
                    <p class="mt-1 text-gray-900">{{ $user->last_login ? $user->last_login->format('Y-m-d H:i:s') : 'Never' }}</p>
                </div>

                <!-- Deleted At (if soft deleted) -->
                @if($user->deleted_at)
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4 text-red-600">Deleted</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-red-600">{{ $user->deleted_at->format('Y-m-d H:i:s') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Button -->
    @if(!$user->deleted_at && $user->id !== auth()->id())
    <div class="mt-6 flex justify-end">
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Delete User
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
