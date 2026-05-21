import axios from 'axios';

// Crea una instancia reusable de Axios
const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL || 'http://veterinaria.test/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const ssoApi = axios.create({
  baseURL: process.env.NEXT_PUBLIC_SSO_URL || 'http://sso.test/login',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Interceptor: adjunta el token Bearer a cada request si existe
api.interceptors.request.use((config) => {
  if (typeof window !== 'undefined') {
    const token = localStorage.getItem('access_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
  }
  return config;
});

// Interceptor de respuesta: si recibimos 401, limpiamos sesión
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401 && typeof window !== 'undefined') {
      localStorage.removeItem('access_token');
      localStorage.removeItem('user_info');
      // Redirigir al login del SSO central
      const callback = encodeURIComponent(window.location.origin + '/auth/callback');
      const baseUrl = process.env.NEXT_PUBLIC_SSO_URL || 'http://sso.test/login';
      window.location.href = `${baseUrl}?callback=${callback}`;
    }
    
    if (error.response?.status === 403) {
      alert('Acceso denegado: No tienes permisos para realizar esta acción.');
    }

    return Promise.reject(error);
  }
);

export default api;