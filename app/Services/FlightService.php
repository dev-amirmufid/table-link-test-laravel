<?php

namespace App\Services;

class FlightService
{
    /**
     * Get flight information from Tiket.com.
     * Note: This is a mock implementation since direct scraping is not reliable.
     * In production, you would use Tiket.com's API or a proper scraping service.
     */
    public function getFlights(): array
    {
        // Mock data for demonstration
        // In production, this would scrape from Tiket.com or use their API
        return [
            [
                'airline' => 'Garuda Indonesia',
                'flight_number' => 'GA 401',
                'departure_time' => '06:30',
                'arrival_time' => '08:45',
                'price' => 1250000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
            [
                'airline' => 'AirAsia',
                'flight_number' => 'QZ 752',
                'departure_time' => '08:00',
                'arrival_time' => '10:15',
                'price' => 850000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
            [
                'airline' => 'Lion Air',
                'flight_number' => 'JT 893',
                'departure_time' => '09:30',
                'arrival_time' => '11:45',
                'price' => 750000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
            [
                'airline' => 'Citilink',
                'flight_number' => 'QG 685',
                'departure_time' => '11:00',
                'arrival_time' => '13:15',
                'price' => 950000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
            [
                'airline' => 'Batik Air',
                'flight_number' => 'ID 6520',
                'departure_time' => '13:30',
                'arrival_time' => '15:45',
                'price' => 1100000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
            [
                'airline' => 'Sriwijaya Air',
                'flight_number' => 'SJ 1012',
                'departure_time' => '15:00',
                'arrival_time' => '17:15',
                'price' => 980000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'class' => 'Economy',
            ],
        ];
    }

    /**
     * Filter flights based on search criteria.
     */
    public function filterFlights(array $criteria): array
    {
        $flights = $this->getFlights();

        // Filter by departure time (before 5:00 PM)
        if (isset($criteria['max_departure_time'])) {
            $flights = array_filter($flights, function($flight) use ($criteria) {
                $departureTime = strtotime($flight['departure_time']);
                $maxTime = strtotime($criteria['max_departure_time']);
                return $departureTime <= $maxTime;
            });
        }

        // Filter by class
        if (isset($criteria['class'])) {
            $flights = array_filter($flights, function($flight) use ($criteria) {
                return $flight['class'] === $criteria['class'];
            });
        }

        return array_values($flights);
    }
}
