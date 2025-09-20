import axios from 'axios';

// Create axios instance with base configuration
const api = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Books API
export const booksAPI = {
  getAll: (params = {}) => api.get('/books', { params }),
  getById: (id) => api.get(`/books/${id}`),
  create: (data) => api.post('/books', data),
  update: (id, data) => api.put(`/books/${id}`, data),
  delete: (id) => api.delete(`/books/${id}`),
  getByCategory: (category) => api.get(`/books/category/${category}`),
  getAnalytics: () => api.get('/analytics/dashboard'),
};

// Search API
export const searchAPI = {
  searchBooks: (query, params = {}) => api.get('/books/search', { params: { q: query, ...params } }),
  advancedSearch: (filters) => api.post('/search/advanced', filters),
  getSuggestions: (query, type = 'all') => api.get('/search/suggestions', { params: { q: query, type } }),
  getFacetedSearch: (query) => api.get('/search/faceted', { params: { q: query } }),
};

// Loans API
export const loansAPI = {
  getAll: (status = 'active') => api.get('/loans', { params: { status } }),
  create: (bookId) => api.post('/loans', { book_id: bookId }),
  return: (loanId) => api.patch(`/loans/${loanId}/return`),
  getHistory: (params = {}) => api.get('/loans/history', { params }),
  getOverdue: () => api.get('/loans/overdue'),
  extend: (loanId) => api.patch(`/loans/${loanId}/extend`),
  getStats: () => api.get('/loans/stats'),
};

// Notifications API
export const notificationsAPI = {
  getAll: (params = {}) => api.get('/notifications', { params }),
  markAsRead: (id) => api.patch(`/notifications/${id}/read`),
  markAllAsRead: () => api.post('/notifications/mark-all-read'),
  delete: (id) => api.delete(`/notifications/${id}`),
  getStats: () => api.get('/notifications/stats'),
  getPreferences: () => api.get('/notifications/preferences'),
  updatePreferences: (data) => api.put('/notifications/preferences', data),
};

// Recommendations API
export const recommendationsAPI = {
  getPopular: (params = {}) => api.get('/recommendations/popular', { params }),
  getTrending: (params = {}) => api.get('/recommendations/trending', { params }),
  getPersonal: (params = {}) => api.get('/recommendations/personal', { params }),
  getSimilar: (bookId, params = {}) => api.get(`/recommendations/similar/${bookId}`, { params }),
  getNewArrivals: (params = {}) => api.get('/recommendations/new-arrivals', { params }),
};

// User API
export const userAPI = {
  getProfile: () => api.get('/user/profile'),
  updateProfile: (data) => api.put('/user/profile', data),
};

// Auth helpers
export const setAuthToken = (token) => {
  localStorage.setItem('auth_token', token);
};

export const removeAuthToken = () => {
  localStorage.removeItem('auth_token');
};

export const getAuthToken = () => {
  return localStorage.getItem('auth_token');
};

export default api;