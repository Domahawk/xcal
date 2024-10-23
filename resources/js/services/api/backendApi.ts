// services/backendApi.js
import axios from 'axios';

const apiUrl = import.meta.env.VITE_API_URL;

const backendApi = axios.create({
    baseURL: apiUrl, // Adjust this to your actual Laravel API base URL if necessary
    headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Optional: Add request/response interceptors for logging or error handling
backendApi.interceptors.request.use(
    (config) => {
        // If you need to attach a CSRF token, uncomment this part
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            config.headers['X-CSRF-TOKEN'] = token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

backendApi.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default backendApi;
