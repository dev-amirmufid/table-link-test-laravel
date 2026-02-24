@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>

    <!-- Loading indicator -->
    <div id="loadingMessage" class="text-center text-gray-500 py-4">Loading dashboard...</div>

    <!-- Stats Cards -->
    <div id="statsCards" class="grid grid-cols-1 md:grid-cols-4 gap-4 hidden">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Users</p>
            <p id="totalUsers" class="text-3xl font-bold text-blue-600">-</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Total Flights</p>
            <p id="totalFlights" class="text-3xl font-bold text-green-600">-</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Admins</p>
            <p id="totalAdmins" class="text-3xl font-bold text-purple-600">-</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-500 text-sm">Regular Users</p>
            <p id="totalRegularUsers" class="text-3xl font-bold text-orange-600">-</p>
        </div>
    </div>

    <!-- Charts -->
    <div id="chartsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
        <!-- Line Chart Component -->
        <div class="md:col-span-2">
            <x-line-chart chartId="lineChart" title="Revenue vs Expenses vs Profit" />
        </div>

        <!-- Bar Chart Component -->
        <div class="md:col-span-2">
            <x-bar-chart chartId="barChart" title="Monthly Revenue vs Expenses" />
        </div>

        <!-- Pie Chart Component -->
        <div class="md:col-span-2">
            <x-pie-chart chartId="pieChart" title="Product Distribution" />
        </div>
    </div>

    <!-- Error display -->
    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"></div>
</div>
@endsection

@vite(['resources/js/pages/dashboard.js'])
