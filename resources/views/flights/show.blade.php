@extends('layouts.app')

@section('title', 'Flight Details')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Flight Details</h1>
        <a href="{{ route('admin.flights.index') }}" class="text-blue-600 hover:text-blue-800">
            &larr; Back to Flights
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Airline</label>
                    <p class="text-gray-900 text-lg">{{ $flight->airline_name }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Flight Number</label>
                    <p class="text-gray-900 text-lg">{{ $flight->flight_number }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Departure Airport</label>
                    <p class="text-gray-900 text-lg">{{ $flight->departure_airport }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Arrival Airport</label>
                    <p class="text-gray-900 text-lg">{{ $flight->arrival_airport }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Departure Time</label>
                    <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Price</label>
                    <p class="text-gray-900 text-lg">Rp {{ number_format($flight->price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Flight Type</label>
                    <p class="text-gray-900 text-lg capitalize">{{ $flight->flight_type }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 text-sm font-bold mb-1">Class</label>
                    <p class="text-gray-900 text-lg capitalize">{{ $flight->class_type }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4">
            <a href="{{ route('admin.flights.index') }}" class="inline-block bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
