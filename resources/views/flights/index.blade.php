@extends('layouts.app')

@section('title', 'Flight Information')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Flight Information</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" onclick="loadFlights()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
        </button>
    </div>
</div>

<!-- Search Criteria -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Search Criteria</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <p class="mb-0">
                    <strong>Route:</strong> Jakarta (CGK) → Bali (DPS) | 
                    <strong>Type:</strong> One-way | 
                    <strong>Class:</strong> Economy | 
                    <strong>Departure:</strong> Before 17:00
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Flight Data Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Available Flights</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Airline</th>
                        <th>Flight Number</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Route</th>
                        <th>Class</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody id="flightsTable">
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="ms-2">Loading flight data...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function loadFlights() {
        const tableBody = document.getElementById('flightsTable');
        
        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Loading flight data...</span>
                </td>
            </tr>
        `;

        try {
            const response = await fetch('/api/flights', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch flight data');
            }

            const data = await response.json();
            const flights = data.flights;

            if (flights.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="bi bi-info-circle"></i> No flights found
                        </td>
                    </tr>
                `;
                return;
            }

            // Clear table
            tableBody.innerHTML = '';

            // Populate table with flight data
            flights.forEach(flight => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${flight.airline}</strong>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${flight.flight_number}</span>
                    </td>
                    <td>${flight.departure_time}</td>
                    <td>${flight.arrival_time}</td>
                    <td>
                        <code>${flight.departure_airport} → ${flight.arrival_airport}</code>
                    </td>
                    <td>
                        <span class="badge bg-info">${flight.class}</span>
                    </td>
                    <td>
                        <strong class="text-success">Rp ${flight.price.toLocaleString('id-ID')}</strong>
                    </td>
                `;
                tableBody.appendChild(row);
            });

        } catch (error) {
            console.error('Error loading flights:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Error loading flight data. Please try again.
                    </td>
                </tr>
            `;
        }
    }

    // Load flights on page load
    document.addEventListener('DOMContentLoaded', loadFlights);
</script>
@endsection
