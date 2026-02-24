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

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" placeholder="Search by name or email..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[150px] hidden">
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select id="sort_by"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="created_at">Created At</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="role">Role</option>
                    <option value="last_login">Last Login</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px] hidden">
                <label for="sort_dir" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <select id="sort_dir"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="filterBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filter
                </button>
                <button id="resetBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" data-sort="name">
                        Name <span class="sort-icon"></span>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" data-sort="email">
                        Email <span class="sort-icon"></span>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" data-sort="role">
                        Role <span class="sort-icon"></span>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" data-sort="last_login">
                        Last Login <span class="sort-icon"></span>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <div id="paginationInfo" class="text-sm text-gray-700"></div>
        <div id="paginationLinks"></div>
    </div>
</div>
@endsection

@vite(['resources/js/pages/users.js'])
