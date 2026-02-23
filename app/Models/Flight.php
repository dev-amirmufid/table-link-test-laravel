<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    /**
     are mass assignable.
     *
     * The attributes that * @var list<string>
     */
    protected $fillable = [
        'airline_name',
        'flight_number',
        'departure_time',
        'price',
        'departure_airport',
        'arrival_airport',
        'flight_type',
        'class_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime:H:i',
            'price' => 'decimal:2',
        ];
    }
}
