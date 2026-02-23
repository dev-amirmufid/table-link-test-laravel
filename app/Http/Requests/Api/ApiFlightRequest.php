<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ApiFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'airline_name' => 'required|string|max:255',
            'flight_number' => 'required|string|max:20',
            'departure_time' => 'required',
            'price' => 'required|numeric|min:0',
            'departure_airport' => 'required|string|max:10',
            'arrival_airport' => 'required|string|max:10',
            'flight_type' => 'required|string|max:50',
            'class_type' => 'required|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'airline_name.required' => 'Airline name is required',
            'airline_name.string' => 'Airline name must be a string',
            'airline_name.max' => 'Airline name may not be greater than 255 characters',
            'flight_number.required' => 'Flight number is required',
            'flight_number.string' => 'Flight number must be a string',
            'flight_number.max' => 'Flight number may not be greater than 20 characters',
            'departure_time.required' => 'Departure time is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be at least 0',
            'departure_airport.required' => 'Departure airport is required',
            'arrival_airport.required' => 'Arrival airport is required',
            'flight_type.required' => 'Flight type is required',
            'class_type.required' => 'Class type is required',
        ];
    }
}
