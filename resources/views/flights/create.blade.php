@extends('layouts.app')

@section('title', 'Add Flight')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Add Flight</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form id="createFlightForm">
            <div class="mb-4">
                <label for="airline_name" class="block text-gray-700 text-sm font-bold mb-2">Airline Name</label>
                <input type="text" id="airline_name" name="airline_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                <p id="airline_nameError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="mb-4">
                <label for="flight_number" class="block text-gray-700 text-sm font-bold mb-2">Flight Number</label>
                <input type="text" id="flight_number" name="flight_number"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="GA 401" required>
                <p id="flight_numberError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="departure_airport" class="block text-gray-700 text-sm font-bold mb-2">Departure Airport</label>
                    <input type="text" id="departure_airport" name="departure_airport" value="CGK"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <p id="departure_airportError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div>
                    <label for="arrival_airport" class="block text-gray-700 text-sm font-bold mb-2">Arrival Airport</label>
                    <input type="text" id="arrival_airport" name="arrival_airport" value="DPS"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <p id="arrival_airportError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="departure_time" class="block text-gray-700 text-sm font-bold mb-2">Departure Time</label>
                    <input type="time" id="departure_time" name="departure_time"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <p id="departure_timeError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
                <div>
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input type="number" id="price" name="price"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="750000" required>
                    <p id="priceError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="flight_type" class="block text-gray-700 text-sm font-bold mb-2">Flight Type</label>
                    <select id="flight_type" name="flight_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="one-way">One Way</option>
                        <option value="round-trip">Round Trip</option>
                    </select>
                </div>
                <div>
                    <label for="class_type" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                    <select id="class_type" name="class_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="economy">Economy</option>
                        <option value="business">Business</option>
                        <option value="first">First Class</option>
                    </select>
                </div>
            </div>

            <div id="successMessage" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative hidden"></div>

            <div class="flex items-center justify-end">
                <a href="{{ route('admin.flights.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
                <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Flight
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@vite(['resources/js/pages/form.js'])
