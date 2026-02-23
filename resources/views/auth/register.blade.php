@extends('auth.layout')

@section('title', 'Register')

@section('content')
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h5 class="text-center mb-4">Create Account</h5>

<form method="POST" action="{{ route('auth.register.post') }}">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
        </div>
        @error('name')
            <div class="text-danger small mt-1">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
        </div>
        @error('email')
            <div class="text-danger small mt-1">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" required placeholder="Create a strong password">
        </div>
        @error('password')
            <div class="text-danger small mt-1">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter your password">
        </div>
    </div>
    
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Create Account
        </button>
    </div>
</form>

<p class="text-center mt-3 mb-0">
    Already have an account? 
    <a href="{{ route('auth.login') }}" class="text-decoration-none">Sign in here</a>
</p>
@endsection
