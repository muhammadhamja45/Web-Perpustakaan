# 🚀 API & React.js Features - Sistem Perpustakaan Digital SMK

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![React](https://img.shields.io/badge/React-18.x-61DAFB?style=for-the-badge&logo=react&logoColor=black)](https://reactjs.org)
[![API](https://img.shields.io/badge/REST-API-00D9FF?style=for-the-badge&logo=api&logoColor=white)](https://restfulapi.net)

> **Modern web application with RESTful API backend and React.js frontend for enhanced user experience**

---

## 🎯 **New Features Added**

### 🔧 **RESTful API Endpoints**
- **Books API** - CRUD operations with advanced filtering
- **Search API** - Advanced search with autocomplete
- **Loans API** - Book borrowing and returning system
- **Notifications API** - Real-time notification management
- **Recommendations API** - AI-powered book recommendations

### ⚛️ **React.js Frontend**
- **Advanced Search** - Real-time search with filters and suggestions
- **Notification Center** - Modern notification management UI
- **Book Recommendations** - Personalized and trending recommendations
- **Responsive Design** - Mobile-first approach with Tailwind CSS

---

## 🌐 **API Documentation**

### **Base URL**
```
http://localhost:8000/api/v1
```

### **Authentication**
```bash
# Include Bearer token in headers
Authorization: Bearer {your-token-here}
```

### **Key Endpoints**

#### 📚 **Books**
```http
GET    /api/v1/books                    # Get all books (paginated)
GET    /api/v1/books/{id}               # Get specific book
POST   /api/v1/books                    # Create book (admin only)
PUT    /api/v1/books/{id}               # Update book (admin only)
DELETE /api/v1/books/{id}               # Delete book (admin only)
```

#### 🔍 **Search**
```http
GET    /api/v1/books/search?q={query}   # Basic search
POST   /api/v1/search/advanced          # Advanced search with filters
GET    /api/v1/search/suggestions       # Autocomplete suggestions
```

#### 📋 **Loans**
```http
GET    /api/v1/loans                    # Get user loans
POST   /api/v1/loans                    # Borrow a book
PATCH  /api/v1/loans/{id}/return        # Return a book
GET    /api/v1/loans/history            # Loan history
```

#### ✨ **Recommendations**
```http
GET    /api/v1/recommendations/popular     # Popular books
GET    /api/v1/recommendations/trending    # Trending books
GET    /api/v1/recommendations/personal    # Personal recommendations
GET    /api/v1/recommendations/similar/{id} # Similar books
```

#### 🔔 **Notifications**
```http
GET    /api/v1/notifications              # Get notifications
PATCH  /api/v1/notifications/{id}/read    # Mark as read
POST   /api/v1/notifications/mark-all-read # Mark all as read
DELETE /api/v1/notifications/{id}         # Delete notification
```

---

## ⚛️ **React.js Components**

### **Component Structure**
```
resources/react/src/
├── components/
│   ├── BookSearchAdvanced.jsx     # Advanced search interface
│   ├── BookRecommendations.jsx    # Recommendation system
│   ├── NotificationCenter.jsx     # Notification management
│   ├── BookCard.jsx               # Book display component
│   └── LoadingSpinner.jsx         # Loading states
├── services/
│   └── api.js                     # API service layer
├── hooks/                         # Custom React hooks
├── utils/                         # Utility functions
└── App.jsx                        # Main application
```

### **Key Features**

#### 🔍 **Advanced Search Component**
```jsx
// Features:
- Real-time search with debouncing
- Autocomplete suggestions
- Advanced filters (author, category, year, etc.)
- Faceted search with result counts
- Responsive design
```

#### 🔔 **Notification Center**
```jsx
// Features:
- Real-time notification updates
- Mark as read/unread functionality
- Filter by status (all/unread/read)
- Delete notifications
- Notification statistics
- Mobile-responsive drawer
```

#### ✨ **Book Recommendations**
```jsx
// Features:
- Personalized recommendations
- Popular and trending books
- Similar book suggestions
- User reading profile analysis
- Category-based recommendations
```

---

## 🚀 **Getting Started**

### **1. Access the Applications**

#### Laravel Web Application
```
http://localhost:8000/dashboard
```

#### React.js Application
```
http://localhost:8000/react-app
```

#### API Documentation
```
http://localhost:8000/api-docs
```

### **2. API Testing Examples**

#### Search Books
```bash
curl -X GET "http://localhost:8000/api/v1/books/search?q=laravel&limit=10" \
  -H "Accept: application/json"
```

#### Advanced Search
```bash
curl -X POST "http://localhost:8000/api/v1/search/advanced" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "programming",
    "category": "technology",
    "available_only": true,
    "sort_by": "title"
  }'
```

#### Borrow a Book
```bash
curl -X POST "http://localhost:8000/api/v1/loans" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {your-token}" \
  -d '{"book_id": 1}'
```

#### Get Recommendations
```bash
curl -X GET "http://localhost:8000/api/v1/recommendations/popular?limit=5" \
  -H "Accept: application/json"
```

---

## 🎨 **Frontend Features**

### **Modern UI/UX**
- **Tailwind CSS** - Utility-first styling
- **Lucide Icons** - Beautiful, consistent icons
- **Responsive Design** - Mobile-first approach
- **Dark Mode Support** - User preference based
- **Loading States** - Smooth user experience
- **Error Handling** - User-friendly error messages

### **Real-time Features**
- **Live Search** - Instant search results
- **Auto-suggestions** - Smart autocomplete
- **Notification Updates** - Real-time notifications
- **Dynamic Recommendations** - Updated based on user activity

### **Performance Optimizations**
- **React Query** - Efficient data fetching and caching
- **Debounced Search** - Reduced API calls
- **Lazy Loading** - Component-based loading
- **Optimistic Updates** - Immediate UI feedback

---

## 🔧 **Technical Implementation**

### **API Architecture**
```php
// Laravel API Controllers
app/Http/Controllers/Api/
├── BookApiController.php           # Book CRUD operations
├── SearchApiController.php         # Search functionality
├── LoanApiController.php           # Loan management
├── NotificationApiController.php   # Notification system
└── RecommendationApiController.php # Recommendation engine
```

### **React Services**
```javascript
// API Service Layer
const api = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Automatic token management
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

### **Database Integration**
```sql
-- New notification system
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY,
    type VARCHAR(255),
    title VARCHAR(255),
    message TEXT,
    data JSON,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP
);
```

---

## 📊 **Recommendation Algorithm**

### **Collaborative Filtering**
```php
// Find users with similar reading patterns
$similarUsers = Loan::whereIn('book_id', $userBooks)
    ->where('user_id', '!=', $user->id)
    ->select('user_id', DB::raw('COUNT(*) as common_books'))
    ->groupBy('user_id')
    ->having('common_books', '>=', 2)
    ->orderBy('common_books', 'desc')
    ->get();
```

### **Content-Based Filtering**
```php
// Recommend books by same authors
$authorBooks = Book::whereIn('author', $userAuthors)
    ->whereNotIn('id', $userBooks)
    ->where('available_quantity', '>', 0)
    ->withCount('loans')
    ->get();
```

### **Trending Analysis**
```php
// Calculate growth rate for trending books
$growth = $book->previous_loans > 0
    ? (($book->recent_loans - $book->previous_loans) / $book->previous_loans) * 100
    : ($book->recent_loans > 0 ? 100 : 0);
```

---

## 🔒 **Security Features**

### **API Security**
- **Sanctum Authentication** - Token-based auth
- **CSRF Protection** - Cross-site request forgery protection
- **Rate Limiting** - API request throttling
- **Input Validation** - Comprehensive request validation
- **SQL Injection Prevention** - Eloquent ORM protection

### **Frontend Security**
- **XSS Protection** - React's built-in protection
- **Content Security Policy** - Secure content loading
- **Secure Token Storage** - Proper token management
- **API Error Handling** - Secure error responses

---

## 📱 **Mobile Responsiveness**

### **Responsive Breakpoints**
```css
/* Tailwind CSS breakpoints */
sm: 640px   /* Small devices */
md: 768px   /* Medium devices */
lg: 1024px  /* Large devices */
xl: 1280px  /* Extra large devices */
```

### **Mobile Features**
- **Touch-friendly Interface** - Optimized for touch
- **Collapsible Sidebar** - Mobile navigation
- **Swipe Gestures** - Natural mobile interactions
- **Optimized Loading** - Fast mobile performance

---

## 🎯 **User Experience**

### **Search Experience**
- **Instant Results** - Real-time search feedback
- **Smart Suggestions** - AI-powered autocomplete
- **Filter Persistence** - Remember user preferences
- **Search History** - Track popular searches

### **Notification Experience**
- **Grouped Notifications** - Organized by type
- **Action Buttons** - Quick actions from notifications
- **Mark as Read** - Efficient notification management
- **Sound/Visual Cues** - Optional notification alerts

### **Recommendation Experience**
- **Personalized Feed** - Based on reading history
- **Explanation Tags** - Why books are recommended
- **Discovery Features** - Trending and new arrivals
- **Similar Books** - Find related content

---

## 🔄 **Future Enhancements**

### **Phase 2 Features**
- [ ] **Real-time Chat** - WebSocket integration
- [ ] **Push Notifications** - Browser notifications
- [ ] **Offline Support** - Service worker integration
- [ ] **Progressive Web App** - PWA capabilities
- [ ] **Advanced Analytics** - User behavior tracking

### **Phase 3 Features**
- [ ] **Machine Learning** - Enhanced recommendations
- [ ] **Voice Search** - Speech recognition
- [ ] **AR Features** - Book scanning with camera
- [ ] **Social Features** - User reviews and ratings

---

## 📚 **Learning Resources**

### **API Development**
- [Laravel API Documentation](https://laravel.com/docs/api-resources)
- [REST API Best Practices](https://restfulapi.net/rest-api-design-tutorial-with-example/)
- [API Authentication with Sanctum](https://laravel.com/docs/sanctum)

### **React Development**
- [React Official Documentation](https://react.dev/)
- [React Query Guide](https://tanstack.com/query/latest)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

### **Full-Stack Integration**
- [Laravel + React Integration](https://laravel.com/docs/frontend#react)
- [API Design Patterns](https://swagger.io/resources/articles/best-practices-in-api-design/)

---

## 🤝 **Contributing**

### **API Endpoints**
1. Follow RESTful conventions
2. Include proper status codes
3. Implement comprehensive validation
4. Add detailed documentation

### **React Components**
1. Use functional components with hooks
2. Implement proper error boundaries
3. Follow accessibility guidelines
4. Write comprehensive tests

### **Code Style**
1. Follow PSR-12 for PHP
2. Use ESLint for JavaScript
3. Implement TypeScript gradually
4. Maintain consistent formatting

---

**🎉 Modern Library Management with API & React.js!**

> This enhanced system combines the power of Laravel's robust backend with React's dynamic frontend, providing a seamless and modern user experience for library management.

---

**📞 Support & Documentation**
- **API Documentation**: [/api-docs](/api-docs)
- **React App**: [/react-app](/react-app)
- **Laravel Dashboard**: [/dashboard](/dashboard)
- **Technical Docs**: [DOCUMENTATION.md](DOCUMENTATION.md)

**🔧 Development Team**: Full Stack Laravel + React Developer
**📅 Last Updated**: September 2025
**🏷️ Version**: 2.0.0 (with API & React features)

---