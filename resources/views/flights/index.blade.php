@extends('layouts.app')

@section('title', 'Flight Information')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Flight Information</h1>
        <div class="space-x-2">
            <form action="{{ route('admin.flights.scrape') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Scrape Flights
                </button>
            </form>
            <a href="{{ route('admin.flights.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Add Flight
            </a>
        </div>
    </div>

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
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($flights as $flight)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $flight->airline_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $flight->flight_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $flight->departure_airport }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $flight->arrival_airport }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($flight->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($flight->class_type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.flights.edit', $flight->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $flights->links() }}
    </div>
</div>
@endsection
