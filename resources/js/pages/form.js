// Form Handler Module
import '../bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Create User Form
    const createUserForm = document.getElementById('createUserForm');
    if (createUserForm) {
        createUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            const successMsg = document.getElementById('successMessage');
            if (successMsg) successMsg.classList.add('hidden');

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';

            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
                role: document.getElementById('role').value,
            };

            try {
                await window.axios.post('/api/users', formData);

                if (successMsg) {
                    successMsg.textContent = 'User created successfully!';
                    successMsg.classList.remove('hidden');
                }

                setTimeout(() => {
                    window.location.href = '/admin/users';
                }, 1000);
            } catch (error) {
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    for (const [key, messages] of Object.entries(errors)) {
                        const errorEl = document.getElementById(`${key}Error`);
                        if (errorEl) {
                            errorEl.textContent = messages[0];
                            errorEl.classList.remove('hidden');
                        }
                    }
                } else {
                    alert('Error creating user');
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create User';
            }
        });
    }

    // Create Flight Form
    const createFlightForm = document.getElementById('createFlightForm');
    if (createFlightForm) {
        createFlightForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Clear errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            const successMsg = document.getElementById('successMessage');
            if (successMsg) successMsg.classList.add('hidden');

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';

            // Get current date and combine with time
            const now = new Date();
            const dateStr = now.toISOString().split('T')[0];
            const timeStr = document.getElementById('departure_time').value;

            const formData = {
                airline_name: document.getElementById('airline_name').value,
                flight_number: document.getElementById('flight_number').value,
                departure_airport: document.getElementById('departure_airport').value,
                arrival_airport: document.getElementById('arrival_airport').value,
                departure_time: dateStr + ' ' + timeStr + ':00',
                price: document.getElementById('price').value,
                flight_type: document.getElementById('flight_type').value,
                class_type: document.getElementById('class_type').value,
            };

            try {
                await window.axios.post('/api/flights', formData);

                if (successMsg) {
                    successMsg.textContent = 'Flight created successfully!';
                    successMsg.classList.remove('hidden');
                }

                setTimeout(() => {
                    window.location.href = '/admin/flights';
                }, 1000);
            } catch (error) {
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    for (const [key, messages] of Object.entries(errors)) {
                        const errorEl = document.getElementById(`${key}Error`);
                        if (errorEl) {
                            errorEl.textContent = messages[0];
                            errorEl.classList.remove('hidden');
                        }
                    }
                } else {
                    alert('Error creating flight');
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Flight';
            }
        });
    }
});
