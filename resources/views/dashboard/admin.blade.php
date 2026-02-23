@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Users</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Flights</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_flights'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Admins</p>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_admins'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Regular Users</p>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['total_regular_users'] }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-lg shadow md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Revenue vs Expenses vs Profit</h3>
            <canvas id="lineChart"></canvas>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-lg shadow md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Monthly Revenue vs Expenses</h3>
            <canvas id="barChart"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-lg shadow md:col-span-2">
            <h3 class="text-lg font-semibold mb-4">Product Distribution</h3>
            <div class="max-w-md mx-auto">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Line Chart - Revenue, Expenses, Profit Margin
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: @json($lineChartData['data']['labels'] ?? []),
            datasets: @json($lineChartData['data']['datasets'] ?? [])
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'USD'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    title: {
                        display: true,
                        text: 'Profit (%)'
                    }
                }
            }
        }
    });

    // Bar Chart - Monthly Revenue vs Expenses
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: @json($barChartData['data']['labels'] ?? []),
            datasets: @json($barChartData['data']['datasets'] ?? [])
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'USD'
                    }
                }
            }
        }
    });

    // Pie Chart - Product Distribution
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: @json($pieChartData['data']['labels'] ?? []),
            datasets: @json($pieChartData['data']['datasets'] ?? [])
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endsection
