// Users Page Module
import '../bootstrap';

let currentPage = 1;
let currentSearch = '';
let currentSortBy = 'created_at';
let currentSortDir = 'desc';

async function loadUsers(page = 1) {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

    try {
        const params = new URLSearchParams({
            page: page,
            search: currentSearch,
            sort_by: currentSortBy,
            sort_dir: currentSortDir,
            per_page: 10
        });

        const response = await window.axios.get(`/api/users?${params}`);
        const data = response.data.users;

        renderUsers(data.data);
        renderPagination(data);
    } catch (error) {
        console.error('Error loading users:', error);
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading users</td></tr>';
    }
}

function renderUsers(users) {
    const tbody = document.getElementById('usersTableBody');

    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found.</td></tr>';
        return;
    }

    tbody.innerHTML = users.map(user => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">${user.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">${user.name}</td>
            <td class="px-6 py-4 whitespace-nowrap">${user.email}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                    ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${user.last_login ? user.last_login : 'Never'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <a href="/admin/users/${user.id}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                <button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        </tr>
    `).join('');
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
        <button onclick="loadUsers(${link.url ? new URL(link.url).searchParams.get('page') : 1})"
            class="px-3 py-1 mx-1 rounded ${link.active ? 'bg-blue-600 text-white' : 'bg-gray-200'}"
            ${!link.url ? 'disabled' : ''}>
            ${link.label}
        </button>
    `).join('');
}

async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    try {
        await window.axios.delete(`/api/users/${id}`);
        loadUsers(currentPage);
    } catch (error) {
        alert('Error deleting user');
    }
}

// Make functions available globally
window.loadUsers = loadUsers;
window.deleteUser = deleteUser;

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    // Filter button
    const filterBtn = document.getElementById('filterBtn');
    if (filterBtn) {
        filterBtn.addEventListener('click', () => {
            currentSearch = document.getElementById('search').value;
            currentSortBy = document.getElementById('sort_by').value;
            currentSortDir = document.getElementById('sort_dir').value;
            loadUsers(1);
        });
    }

    // Reset button
    const resetBtn = document.getElementById('resetBtn');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            document.getElementById('search').value = '';
            document.getElementById('sort_by').value = 'created_at';
            document.getElementById('sort_dir').value = 'desc';
            currentSearch = '';
            currentSortBy = 'created_at';
            currentSortDir = 'desc';
            loadUsers(1);
        });
    }

    // Initial load
    loadUsers();
});
