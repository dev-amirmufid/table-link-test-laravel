@extends('layouts.app')

@section('title', 'Flight Information')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Flight Information</h1>
        <div class="space-x-2">
            <button id="scrapeBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Scrape Flights
            </button>
            <a href="{{ route('admin.flights.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Add Flight
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" placeholder="Search by airline or flight number..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <!-- Loading indicator -->
    <div id="loadingMessage" class="bg-white rounded-lg shadow p-4 text-center text-gray-500">
        Loading flights...
    </div>

    <!-- Flights Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Airline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flight No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Arrival</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="flightsTableBody" class="bg-white divide-y divide-gray-200">
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <div id="paginationInfo" class="text-sm text-gray-700"></div>
        <div id="paginationLinks"></div>
    </div>
</div>

<!-- Scrape Result Modal -->
<div id="scrapeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Scraping Flights</h3>
            <div class="mt-2 px-7 py-3">
                <p id="scrapeMessage" class="text-sm text-gray-500">Processing...</p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeScrapeModal" class="px-4 bg-gray-500 text-white rounded-md hover:bg-gray-600 hidden">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@vite(['resources/js/pages/flights.js'])
