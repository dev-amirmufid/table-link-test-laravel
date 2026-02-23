@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Welcome, {{ $user->name }}!</h1>
    <p class="text-gray-600">This is your dashboard. You are logged in as a regular user.</p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <h3 class="font-semibold text-blue-800">Your Profile</h3>
            <p class="text-sm text-blue-600 mt-2">Email: {{ $user->email }}</p>
            <p class="text-sm text-blue-600">Role: {{ ucfirst($user->role) }}</p>
            @if($user->last_login)
                <p class="text-sm text-blue-600">Last Login: {{ $user->last_login->format('Y-m-d H:i') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
