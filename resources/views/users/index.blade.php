@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">User Management</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Add User
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ $search ?? '' }}"
                    placeholder="Search by name or email..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        @php
                            $nameDir = ($sortBy == 'name' && $sortDir == 'asc') ? 'desc' : 'asc';
                            $nameSort = ($sortBy == 'name') ? ($sortDir == 'asc' ? '↓' : '↑') : '';
                        @endphp
                        <a href="{{ route('admin.users.index', ['sort_by' => 'name', 'sort_dir' => $nameDir]) }}" class="flex items-center gap-1 hover:text-gray-700">
                            Name {{ $nameSort }}
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        @php
                            $emailDir = ($sortBy == 'email' && $sortDir == 'asc') ? 'desc' : 'asc';
                            $emailSort = ($sortBy == 'email') ? ($sortDir == 'asc' ? '↓' : '↑') : '';
                        @endphp
                        <a href="{{ route('admin.users.index', ['sort_by' => 'email', 'sort_dir' => $emailDir]) }}" class="flex items-center gap-1 hover:text-gray-700">
                            Email {{ $emailSort }}
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        @php
                            $roleDir = ($sortBy == 'role' && $sortDir == 'asc') ? 'desc' : 'asc';
                            $roleSort = ($sortBy == 'role') ? ($sortDir == 'asc' ? '↓' : '↑') : '';
                        @endphp
                        <a href="{{ route('admin.users.index', ['sort_by' => 'role', 'sort_dir' => $roleDir]) }}" class="flex items-center gap-1 hover:text-gray-700">
                            Role {{ $roleSort }}
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        @php
                            $loginDir = ($sortBy == 'last_login' && $sortDir == 'asc') ? 'desc' : 'asc';
                            $loginSort = ($sortBy == 'last_login') ? ($sortDir == 'asc' ? '↓' : '↑') : '';
                        @endphp
                        <a href="{{ route('admin.users.index', ['sort_by' => 'last_login', 'sort_dir' => $loginDir]) }}" class="flex items-center gap-1 hover:text-gray-700">
                            Last Login {{ $loginSort }}
                        </a>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $user->last_login ? $user->last_login->format('Y-m-d H:i') : 'Never' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection
