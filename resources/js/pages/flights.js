// Flights Page Module
import '../bootstrap';

let currentPage = 1;
let currentSearch = '';
let currentClassType = '';

async function loadFlights(page = 1) {
    const tbody = document.getElementById('flightsTableBody');
    const loadingMessage = document.getElementById('loadingMessage');

    tbody.innerHTML = '';
    loadingMessage.classList.remove('hidden');

    try {
        const params = new URLSearchParams({
            page: page,
            search: currentSearch,
            per_page: 10
        });

        const response = await window.axios.get(`/api/flights?${params}`);
        const data = response.data.flights;

        loadingMessage.classList.add('hidden');
        renderFlights(data.data);
        renderPagination(data);
    } catch (error) {
        console.error('Error loading flights:', error);
        loadingMessage.textContent = 'Error loading flights';
    }
}

function renderFlights(flights) {
    const tbody = document.getElementById('flightsTableBody');

    if (flights.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">No flights found.</td></tr>';
        return;
    }

    tbody.innerHTML = flights.map(flight => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">${flight.airline_name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${flight.flight_number}</td>
            <td class="px-6 py-4 whitespace-nowrap">${flight.departure_airport}</td>
            <td class="px-6 py-4 whitespace-nowrap">${flight.arrival_airport}</td>
            <td class="px-6 py-4 whitespace-nowrap">${formatTime(flight.departure_time)}</td>
            <td class="px-6 py-4 whitespace-nowrap">${formatPrice(flight.price)}</td>
            <td class="px-6 py-4 whitespace-nowrap capitalize">${flight.class_type}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <a href="/admin/flights/${flight.id}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                <button onclick="deleteFlight(${flight.id})" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        </tr>
    `).join('');
}

function formatTime(dateTime) {
    const date = new Date(dateTime);
    return date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
}

function formatPrice(price) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
}

function renderPagination(data) {
    document.getElementById('paginationInfo').textContent =
        `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;

    const links = document.getElementById('paginationLinks');
    if (!data.links) {
        links.innerHTML = '';
        return;
    }

    links.innerHTML = data.links.map(link => `
        <button onclick="loadFlights(${link.url ? new URL(link.url).searchParams.get('page') : 1})"
            class="px-3 py-1 mx-1 rounded ${link.active ? 'bg-blue-600 text-white' : 'bg-gray-200'}"
            ${!link.url ? 'disabled' : ''}>
            ${link.label}
        </button>
    `).join('');
}

async function deleteFlight(id) {
    if (!confirm('Are you sure you want to delete this flight?')) return;

    try {
        await window.axios.delete(`/api/flights/${id}`);
        loadFlights(currentPage);
    } catch (error) {
        alert('Error deleting flight');
    }
}

// Make functions available globally
window.loadFlights = loadFlights;
window.deleteFlight = deleteFlight;

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    // Scrape button handler
    const scrapeBtn = document.getElementById('scrapeBtn');
    if (scrapeBtn) {
        scrapeBtn.addEventListener('click', async function() {
            const modal = document.getElementById('scrapeModal');
            const message = document.getElementById('scrapeMessage');
            const closeBtn = document.getElementById('closeScrapeModal');

            modal.classList.remove('hidden');
            closeBtn.classList.add('hidden');
            message.textContent = 'Scraping flights...';

            try {
                const response = await window.axios.post('/api/flights/scrape');
                message.textContent = response.data.message || 'Flights scraped successfully!';
                closeBtn.classList.remove('hidden');
                loadFlights(1);
            } catch (error) {
                message.textContent = 'Error scraping flights: ' + (error.response?.data?.message || error.message);
                closeBtn.classList.remove('hidden');
            }
        });
    }

    // Close modal
    const closeScrapeModal = document.getElementById('closeScrapeModal');
    if (closeScrapeModal) {
        closeScrapeModal.addEventListener('click', function() {
            document.getElementById('scrapeModal').classList.add('hidden');
        });
    }

    // Filter button
    const filterBtn = document.getElementById('filterBtn');
    if (filterBtn) {
        filterBtn.addEventListener('click', () => {
            currentSearch = document.getElementById('search').value;
            
            loadFlights(1);
        });
    }

    // Reset button
    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            document.getElementById('search').value = '';
            
            currentSearch = '';
            currentClassType = '';
            loadFlights(1);
        });
    }

    // Initial load
    loadFlights();
});
