import axios from 'axios';

// Create axios instance - session-based auth with Sanctum
const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    // Sanctum SPA: credentials (cookies) are sent automatically
    withCredentials: true,
    // Don't use X-CSRF-TOKEN for API calls when using session auth
    xsrfCookieName: null,
    xsrfHeaderName: null,
});

// Request interceptor for logging
api.interceptors.request.use(
    (config) => {
        console.log('[API Request]', config.method?.toUpperCase(), config.url);
        return config;
    },
    (error) => {
        console.error('[API Request Error]', error);
        return Promise.reject(error);
    }
);

// Response interceptor for error handling
api.interceptors.response.use(
    (response) => {
        console.log('[API Response]', response.status, response.config.url);
        return response;
    },
    (error) => {
        console.error('[API Error]', error.response?.status, error.response?.data, error.config?.url);

        if (error.response) {
            const status = error.response.status;

            // Unauthorized - redirect to login
            if (status === 401) {
                console.log('[API] 401 Unauthorized, redirecting to login...');
                window.location.href = '/login';
            }

            // Forbidden - show error message
            if (status === 403) {
                const message = error.response.data?.message || 'Access denied';
                console.log('[API] 403 Forbidden:', message);
                alert(message);
            }

            // Server error
            if (status >= 500) {
                console.error('[API] Server error:', error.response.data);
            }
        } else {
            console.error('[API] Network error:', error.message);
        }
        return Promise.reject(error);
    }
);

// Export convenience methods
export const apiGet = (url, params) => api.get(url, { params });
export const apiPost = (url, data) => api.post(url, data);
export const apiPut = (url, data) => api.put(url, data);
export const apiDelete = (url) => api.delete(url);

export default api;
