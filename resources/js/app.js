import './bootstrap';
import api, { apiGet, apiPost, apiPut, apiDelete } from './api';

// Make available globally
window.api = api;
window.apiGet = apiGet;
window.apiPost = apiPost;
window.apiPut = apiPut;
window.apiDelete = apiDelete;
