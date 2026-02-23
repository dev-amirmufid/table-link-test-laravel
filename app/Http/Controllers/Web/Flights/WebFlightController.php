<?php

namespace App\Http\Controllers\Web\Flights;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebFlightRequest;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebFlightController extends Controller
{
    /**
     * List flights
     */
    public function index(Request $request): View
    {
        try {
            $perPage = $request->query('per_page', 10);
            $flights = Flight::orderBy('departure_time', 'asc')
                ->paginate($perPage);

            return view('flights.index', compact('flights'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load flights: ' . $e->getMessage());
        }
    }

    /**
     * Show create flight form
     */
    public function create(): View
    {
        return view('flights.create');
    }

    /**
     * Store new flight
     */
    public function store(WebFlightRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            Flight::create($validated);

            return redirect()->route('admin.flights.index')->with('success', 'Flight created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create flight: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit flight form
     */
    public function edit(int $id): View
    {
        try {
            $flight = Flight::findOrFail($id);
            return view('flights.edit', compact('flight'));
        } catch (\Exception $e) {
            return back()->with('error', 'Flight not found');
        }
    }

    /**
     * Update flight
     */
    public function update(WebFlightRequest $request, int $id): RedirectResponse
    {
        try {
            $flight = Flight::findOrFail($id);
            $validated = $request->validated();

            $flight->update($validated);

            return redirect()->route('admin.flights.index')->with('success', 'Flight updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update flight: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete flight
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $flight = Flight::findOrFail($id);
            $flight->delete();

            return redirect()->route('admin.flights.index')->with('success', 'Flight deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete flight: ' . $e->getMessage());
        }
    }

    /**
     * Scrape flights (mockup)
     */
    public function scrape(): RedirectResponse
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

            $savedCount = 0;
            foreach ($mockupFlights as $flightData) {
                $flight = Flight::updateOrCreate(
                    [
                        'flight_number' => $flightData['flight_number'],
                        'departure_time' => $flightData['departure_time'],
                    ],
                    $flightData
                );
                if ($flight) {
                    $savedCount++;
                }
            }

            return redirect()->route('admin.flights.index')->with('success', $savedCount . ' flights scraped successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to scrape flights: ' . $e->getMessage());
        }
    }
}
