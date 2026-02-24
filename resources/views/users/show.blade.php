@extends('layouts.app')

@section('title', 'User Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">User Detail</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
            <a id="editLink" href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
        </div>
    </div>

    <!-- Loading -->
    <div id="loadingMessage" class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
        Loading user data...
    </div>

    <!-- User Details -->
    <div id="userDetails" class="bg-white rounded-lg shadow overflow-hidden hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="col-span-2">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Basic Information</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">ID</label>
                    <p id="userId" class="mt-1 text-gray-900">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Name</label>
                    <p id="userName" class="mt-1 text-gray-900">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p id="userEmail" class="mt-1 text-gray-900">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Role</label>
                    <p class="mt-1">
                        <span id="userRole" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                            -
                        </span>
                    </p>
                </div>

                <!-- Timestamps -->
                <div class="col-span-2 mt-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Timestamps</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Created At</label>
                    <p id="createdAt" class="mt-1 text-gray-900">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Updated At</label>
                    <p id="updatedAt" class="mt-1 text-gray-900">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Last Login</label>
                    <p id="lastLogin" class="mt-1 text-gray-900">-</p>
                </div>

                <!-- Deleted At -->
                <div id="deletedSection" class="col-span-2 mt-4 hidden">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4 text-red-600">Deleted</h3>
                </div>

                <div id="deletedAt" class="hidden">
                    <label class="block text-sm font-medium text-gray-500">Deleted At</label>
                    <p class="mt-1 text-red-600">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Button -->
    <div id="deleteSection" class="mt-6 flex justify-end hidden">
        <button id="deleteBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Delete User
        </button>
    </div>
</div>

@push('scripts')
<script>
const userId = {{ $userId }};

document.addEventListener('DOMContentLoaded', async function() {
    try {
        const response = await apiGet(`/users/${userId}`);
        const user = response.data.user;

        // Update user details
        document.getElementById('userId').textContent = user.id;
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userEmail').textContent = user.email;
        document.getElementById('createdAt').textContent = formatDate(user.created_at);
        document.getElementById('updatedAt').textContent = formatDate(user.updated_at);
        document.getElementById('lastLogin').textContent = user.last_login ? formatDate(user.last_login) : 'Never';

        // Update role badge
        const roleEl = document.getElementById('userRole');
        roleEl.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);
        roleEl.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}`;

        // Update edit link
        document.getElementById('editLink').href = `/admin/users/${user.id}/edit`;

        // Show deleted info if applicable
        if (user.deleted_at) {
            document.getElementById('deletedSection').classList.remove('hidden');
            document.getElementById('deletedAt').classList.remove('hidden');
            document.getElementById('deletedAt').querySelector('p').textContent = formatDate(user.deleted_at);
            document.getElementById('deleteSection').classList.add('hidden');
        } else {
            document.getElementById('deleteSection').classList.remove('hidden');
        }

        // Show content
        document.getElementById('loadingMessage').classList.add('hidden');
        document.getElementById('userDetails').classList.remove('hidden');

    } catch (error) {
        console.error('Error loading user:', error);
        document.getElementById('loadingMessage').textContent = 'Error loading user data';
    }
});

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toISOString().replace('T', ' ').substring(0, 19);
}

// Delete handler
document.getElementById('deleteBtn')?.addEventListener('click', async function() {
    if (!confirm('Are you sure you want to delete this user?')) return;

    try {
        await apiDelete(`/users/${userId}`);
        window.location.href = '{{ route("admin.users.index") }}';
    } catch (error) {
        alert('Error deleting user');
    }
});
</script>
@endpush
@endsection
