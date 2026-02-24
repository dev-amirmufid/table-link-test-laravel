<?php

namespace App\Http\Controllers\Web\Flights;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\WebFlightRequest;
use App\Services\FlightService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebFlightController extends Controller
{
    protected $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * List flights
     */
    public function index(): View
    {
        return view('flights.index');
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
        $this->flightService->create($request->validated());
        return redirect()->route('admin.flights.index')->with('success', 'Flight created successfully');
    }

    /**
     * Show flight details
     */
    public function show(int $id): View
    {
        $flight = $this->flightService->getById($id);
        return view('flights.show', ['flight' => $flight]);
    }

    /**
     * Delete flight
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->flightService->delete($id);
        return redirect()->route('admin.flights.index')->with('success', 'Flight deleted successfully');
    }

    /**
     * Scrape flights (mockup)
     */
    public function scrape(): RedirectResponse
    {
        $mockupFlights = [
            ['airline_name' => 'Garuda Indonesia', 'flight_number' => 'GA 401', 'departure_time' => '06:00:00', 'price' => 1250000, 'departure_airport' => 'CGK', 'arrival_airport' => 'DPS', 'flight_type' => 'one-way', 'class_type' => 'economy'],
            ['airline_name' => 'Citilink', 'flight_number' => 'QG 501', 'departure_time' => '08:30:00', 'price' => 850000, 'departure_airport' => 'CGK', 'arrival_airport' => 'DPS', 'flight_type' => 'one-way', 'class_type' => 'economy'],
            ['airline_name' => 'Lion Air', 'flight_number' => 'JT 701', 'departure_time' => '14:00:00', 'price' => 750000, 'departure_airport' => 'CGK', 'arrival_airport' => 'DPS', 'flight_type' => 'one-way', 'class_type' => 'economy'],
        ];

        $savedCount = 0;
        foreach ($mockupFlights as $flightData) {
            $this->flightService->create($flightData);
            $savedCount++;
        }

        return redirect()->route('admin.flights.index')->with('success', "{$savedCount} flights scraped successfully");
    }
}
