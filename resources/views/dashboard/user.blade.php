@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">User Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Profile</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Settings</button>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title">
                            <i class="bi bi-person-circle me-2"></i>Welcome back, {{ auth()->user()->name }}!
                        </h4>
                        <p class="card-text">
                            You are logged in as a <strong>{{ ucfirst(auth()->user()->role) }}</strong> user.
                            @if(auth()->user()->last_login)
                                <br>Last login: {{ auth()->user()->last_login->format('Y-m-d H:i') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="display-4">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Information -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Account Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>
                            <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : 'primary' }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Member Since:</strong></td>
                        <td>{{ auth()->user()->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-shield-check me-2"></i>Permissions
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-speedometer2 me-2"></i>View Dashboard</span>
                        <i class="bi bi-check-circle text-success"></i>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-people me-2"></i>User Management</span>
                        <i class="bi bi-x-circle text-danger"></i>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-airplane me-2"></i>Flight Information</span>
                        <i class="bi bi-x-circle text-danger"></i>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-gear me-2"></i>System Settings</span>
                        <i class="bi bi-x-circle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" disabled>
                                <i class="bi bi-person-plus me-2"></i>Add User
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-info" disabled>
                                <i class="bi bi-airplane me-2"></i>View Flights
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-success" disabled>
                                <i class="bi bi-file-earmark-text me-2"></i>Reports
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-warning" disabled>
                                <i class="bi bi-gear me-2"></i>Settings
                            </button>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Notice:</strong> Some features are restricted to admin users only. 
                    Contact your administrator if you need additional permissions.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
