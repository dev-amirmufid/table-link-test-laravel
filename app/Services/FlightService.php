<?php

namespace App\Services;

use App\Models\Flight;
use Illuminate\Support\Facades\Log;

class FlightService
{
    /**
     * Get all flights with pagination, search, and sorting
     * Default filters: CGK->DPS, before 17:00, economy class
     */
    public function getAll(array $params)
    {
        Log::info('FlightService: getAll called', $params);

        $perPage = $params['per_page'] ?? 10;
        $search = $params['search'] ?? '';
        $sortBy = $params['sort_by'] ?? 'departure_time';
        $sortDir = $params['sort_dir'] ?? 'asc';

        // Check if filters should be applied (default: true)
        $applyFilters = $params['apply_filters'] ?? true;

        $query = Flight::query();

        // Apply default flight criteria if not disabled
        if ($applyFilters) {
            // Departure: CGK (Jakarta)
            $query->where('departure_airport', 'CGK');
            // Arrival: DPS (Bali)
            $query->where('arrival_airport', 'DPS');
            // Class: Economy
            $query->where('class_type', 'economy');
            // Flight type: One-way
            $query->where('flight_type', 'one-way');
            // Before 17:00 (5 PM)
            $query->whereTime('departure_time', '<', '17:00:00');
        }

        if ($search) {
            Log::debug("FlightService: Searching flights with term: {$search}");
            $query->where(function ($q) use ($search) {
                $q->where('flight_number', 'like', "%{$search}%")
                  ->orWhere('airline_name', 'like', "%{$search}%")
                  ->orWhere('departure_airport', 'like', "%{$search}%")
                  ->orWhere('arrival_airport', 'like', "%{$search}%");
            });
        }

        $allowedSorts = ['flight_number', 'airline_name', 'departure_airport', 'arrival_airport', 'departure_time', 'price', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('departure_time', 'asc');
        }

        $result = $query->paginate($perPage)->appends($params);
        Log::debug("FlightService: Found {$result->total()} flights");

        return $result;
    }

    /**
     * Get flight by ID
     */
    public function getById(int $id): ?Flight
    {
        Log::info("FlightService: getById called with id: {$id}");

        $flight = Flight::findOrFail($id);
        Log::debug("FlightService: Found flight: {$flight->flight_number}");

        return $flight;
    }

    /**
     * Create new flight
     */
    public function create(array $data): Flight
    {
        Log::info('FlightService: create called', ['flight_number' => $data['flight_number'] ?? 'N/A']);

        $flight = Flight::create($data);

        Log::info("FlightService: Flight created successfully with id: {$flight->id}");

        return $flight;
    }

    /**
     * Update flight
     */
    public function update(int $id, array $data): Flight
    {
        Log::info("FlightService: update called for flight id: {$id}");

        $flight = Flight::findOrFail($id);
        $flight->update($data);

        Log::info("FlightService: Flight {$id} updated successfully");

        return $flight;
    }

    /**
     * Delete flight
     */
    public function delete(int $id): void
    {
        Log::info("FlightService: delete called for flight id: {$id}");

        $flight = Flight::findOrFail($id);
        $flight->delete();

        Log::info("FlightService: Flight {$id} deleted successfully");
    }

    /**
     * Get flight statistics
     */
    public function getStats(): array
    {
        Log::debug('FlightService: getStats called');

        $stats = [
            'total_flights' => Flight::count(),
        ];

        Log::debug('FlightService: Stats retrieved', $stats);

        return $stats;
    }
}
