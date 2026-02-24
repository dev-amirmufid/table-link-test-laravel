@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create New User</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form id="createUserForm">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p id="nameError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <select id="role" name="role"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <p id="roleError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div id="successMessage" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative hidden"></div>

            <div class="flex items-center justify-end">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
                <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@vite(['resources/js/pages/form.js'])
