<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightAPIController extends Controller
{
    protected FlightService $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    /**
     * Get flight information from Tiket.com.
     */
    public function index(Request $request): JsonResponse
    {
        $flights = $this->flightService->getFlights();

        return response()->json([
            'flights' => $flights,
        ]);
    }
}
