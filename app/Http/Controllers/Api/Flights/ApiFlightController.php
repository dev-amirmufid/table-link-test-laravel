<?php

namespace App\Http\Controllers\Api\Flights;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiFlightRequest;
use App\Models\Flight;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiFlightController extends Controller
{
    protected $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Create new flight
     */
    public function store(ApiFlightRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $flight = $this->flightService->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Flight created successfully',
                'flight' => $flight,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create flight: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List flights
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->query();
            $flights = $this->flightService->getAll($params);

            return response()->json([
                'success' => true,
                'flights' => $flights,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load flights: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scrape flights (mockup)
     */
    public function scrape(): JsonResponse
    {
        try {
            // Mockup data - real scraping skipped
            $mockupFlights = [
                [
                    'airline_name' => 'Garuda Indonesia',
                    'flight_number' => 'GA 401',
                    'departure_time' => '06:00:00',
                    'price' => 1250000,
                    'departure_airport' => 'CGK',
                    'arrival_airport' => 'DPS',
                    'flight_type' => 'one-way',
                    'class_type' => 'economy',
                ],
                [
                    'airline_name' => 'Citilink',
                    'flight_number' => 'QG 501',
                    'departure_time' => '08:30:00',
                    'price' => 850000,
                    'departure_airport' => 'CGK',
                    'arrival_airport' => 'DPS',
                    'flight_type' => 'one-way',
                    'class_type' => 'economy',
                ],
                [
                    'airline_name' => 'Lion Air',
                    'flight_number' => 'JT 701',
                    'departure_time' => '14:00:00',
                    'price' => 750000,
                    'departure_airport' => 'CGK',
                    'arrival_airport' => 'DPS',
                    'flight_type' => 'one-way',
                    'class_type' => 'economy',
                ],
                [
                    'airline_name' => 'Batik Air',
                    'flight_number' => 'ID 601',
                    'departure_time' => '10:15:00',
                    'price' => 950000,
                    'departure_airport' => 'CGK',
                    'arrival_airport' => 'DPS',
                    'flight_type' => 'one-way',
                    'class_type' => 'economy',
                ],
                [
                    'airline_name' => 'AirAsia',
                    'flight_number' => 'QZ 201',
                    'departure_time' => '16:30:00',
                    'price' => 680000,
                    'departure_airport' => 'CGK',
                    'arrival_airport' => 'DPS',
                    'flight_type' => 'one-way',
                    'class_type' => 'economy',
                ],
            ];

            $savedFlights = [];
            foreach ($mockupFlights as $flightData) {
                $flight = Flight::updateOrCreate(
                    [
                        'flight_number' => $flightData['flight_number'],
                        'departure_time' => $flightData['departure_time'],
                    ],
                    $flightData
                );
                $savedFlights[] = $flight;
            }

            return response()->json([
                'success' => true,
                'message' => count($savedFlights) . ' flights scraped successfully',
                'data' => $savedFlights,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to scrape flights: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get flight detail
     */
    public function show(int $id): JsonResponse
    {
        try {
            $flight = $this->flightService->getById($id);

            return response()->json([
                'success' => true,
                'flight' => $flight,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Flight not found',
            ], 404);
        }
    }

    /**
     * Update flight
     */
    public function update(ApiFlightRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $flight = $this->flightService->update($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Flight updated successfully',
                'flight' => $flight,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update flight: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete flight
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->flightService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Flight deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete flight: ' . $e->getMessage(),
            ], 500);
        }
    }
}
