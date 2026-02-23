@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Today</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">This Week</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">This Month</button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text display-4">{{ App\Models\User::count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Admin Users</h5>
                <p class="card-text display-4">{{ App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Regular Users</h5>
                <p class="card-text display-4">{{ App\Models\User::where('role', 'user')->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Active Today</h5>
                <p class="card-text display-4">{{ App\Models\User::whereDate('last_login', today())->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Registration Trend</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Roles Distribution</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">User Activity</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fetch chart data from API
    async function loadCharts() {
        try {
            const response = await fetch('/dashboard/data', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch dashboard data');
            }

            const data = await response.json();

            // Line Chart - User Registration
            new Chart(document.getElementById('usersChart'), {
                type: 'line',
                data: data.usersChart,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Pie Chart - Roles Distribution
            new Chart(document.getElementById('rolesChart'), {
                type: 'pie',
                data: data.rolesChart,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // Bar Chart - User Activity
            new Chart(document.getElementById('activityChart'), {
                type: 'bar',
                data: data.activityChart,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error loading charts:', error);
            alert('Error loading dashboard data. Please refresh the page.');
        }
    }

    document.addEventListener('DOMContentLoaded', loadCharts);
</script>
@endsection
