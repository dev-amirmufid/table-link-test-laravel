@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Login to TableLink</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
        </p>

        <div class="mt-6 p-4 bg-gray-100 rounded-md">
            <p class="text-sm text-gray-600 font-semibold mb-2">Demo Credentials:</p>
            <p class="text-xs text-gray-500">Admin: admin@tablelink.com / 12345678</p>
            <p class="text-xs text-gray-500">User: ahmad@example.com / password123</p>
        </div>
    </div>
</div>
@endsection
