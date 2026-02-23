<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    protected $model = Flight::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airlines = [
            'Garuda Indonesia',
            'Citilink',
            'Lion Air',
            'Batik Air',
            'Sriwijaya Air',
            'Wings Air',
            'AirAsia',
            'NAM Air',
        ];

        return [
            'airline_name' => $this->faker->randomElement($airlines),
            'flight_number' => strtoupper($this->faker->lexify('??')) . ' ' . $this->faker->numerify('###'),
            'departure_time' => $this->faker->time('H:i:s'),
            'price' => $this->faker->randomFloat(2, 500000, 2500000),
            'departure_airport' => 'CGK',
            'arrival_airport' => 'DPS',
            'flight_type' => 'one-way',
            'class_type' => 'economy',
        ];
    }
}
