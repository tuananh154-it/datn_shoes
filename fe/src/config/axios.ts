import axios from "axios";

export const api = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        "Content-Type": "application/json",
    },
});

// Interceptor để cập nhật token trước mỗi request
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});
