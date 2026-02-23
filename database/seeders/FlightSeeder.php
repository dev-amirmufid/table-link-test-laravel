<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates: 10 sample flights
     */
    public function run(): void
    {
        $flights = [
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
            [
                'airline_name' => 'Garuda Indonesia',
                'flight_number' => 'GA 403',
                'departure_time' => '07:30:00',
                'price' => 1350000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy',
            ],
            [
                'airline_name' => 'Sriwijaya Air',
                'flight_number' => 'SJ 901',
                'departure_time' => '11:00:00',
                'price' => 720000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy',
            ],
            [
                'airline_name' => 'Wings Air',
                'flight_number' => 'WN 301',
                'departure_time' => '13:45:00',
                'price' => 650000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy',
            ],
            [
                'airline_name' => 'NAM Air',
                'flight_number' => 'IN 501',
                'departure_time' => '15:20:00',
                'price' => 780000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy',
            ],
            [
                'airline_name' => 'Citilink',
                'flight_number' => 'QG 503',
                'departure_time' => '17:00:00',
                'price' => 920000,
                'departure_airport' => 'CGK',
                'arrival_airport' => 'DPS',
                'flight_type' => 'one-way',
                'class_type' => 'economy',
            ],
        ];

        foreach ($flights as $flightData) {
            Flight::updateOrCreate(
                ['flight_number' => $flightData['flight_number']],
                $flightData
            );
        }

        $this->command->info('Seeded: 10 flights');
    }
}
