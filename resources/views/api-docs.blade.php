<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Documentation - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
        }
        .method-get { @apply bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-mono; }
        .method-post { @apply bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-mono; }
        .method-put { @apply bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-mono; }
        .method-delete { @apply bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-mono; }
        .method-patch { @apply bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-mono; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">API Documentation</h1>
                        <p class="text-gray-600">Sistem Perpustakaan Digital SMK - REST API v1</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="/react-app" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            React App Demo
                        </a>
                        <a href="/dashboard" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50">
                            Laravel Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Introduction -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Getting Started</h2>
                <div class="prose max-w-none">
                    <p class="text-gray-600 mb-4">
                        Welcome to the Library Management System API. This RESTful API provides endpoints for managing books, loans, notifications, and recommendations.
                    </p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">Base URL</h3>
                            <div class="code-block">{{ config('app.url') }}/api/v1</div>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Authentication</h3>
                            <div class="code-block">Authorization: Bearer {token}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Endpoints -->
            <div class="space-y-8">
                <!-- Books API -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        📚 Books API
                    </h2>

                    <div class="space-y-4">
                        <!-- Get Books -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/books</code>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Get paginated list of books</p>
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600">Show example</summary>
                                <div class="mt-2 code-block">
curl -X GET "{{ config('app.url') }}/api/v1/books?per_page=10&sort_by=title" \
  -H "Accept: application/json"
                                </div>
                            </details>
                        </div>

                        <!-- Get Book by ID -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/books/{id}</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get specific book details</p>
                        </div>

                        <!-- Create Book (Admin) -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-post">POST</span>
                                <code class="text-sm">/books</code>
                                <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">Admin Only</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Create a new book</p>
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600">Show example</summary>
                                <div class="mt-2 code-block">
curl -X POST "{{ config('app.url') }}/api/v1/books" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "title": "Laravel 11 Guide",
    "author": "John Doe",
    "isbn": "978-123-456-789-0",
    "published_year": 2024,
    "quantity": 5,
    "category": "Programming"
  }'
                                </div>
                            </details>
                        </div>
                    </div>
                </div>

                <!-- Search API -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        🔍 Search API
                    </h2>

                    <div class="space-y-4">
                        <!-- Basic Search -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/books/search</code>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Search books by title, author, or ISBN</p>
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600">Show example</summary>
                                <div class="mt-2 code-block">
curl -X GET "{{ config('app.url') }}/api/v1/books/search?q=laravel&limit=20" \
  -H "Accept: application/json"
                                </div>
                            </details>
                        </div>

                        <!-- Advanced Search -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-post">POST</span>
                                <code class="text-sm">/search/advanced</code>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Advanced search with multiple filters</p>
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600">Show example</summary>
                                <div class="mt-2 code-block">
curl -X POST "{{ config('app.url') }}/api/v1/search/advanced" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "php",
    "author": "john",
    "category": "programming",
    "year_from": 2020,
    "available_only": true,
    "sort_by": "title",
    "per_page": 15
  }'
                                </div>
                            </details>
                        </div>

                        <!-- Search Suggestions -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/search/suggestions</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get autocomplete suggestions</p>
                        </div>
                    </div>
                </div>

                <!-- Loans API -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        📋 Loans API
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded ml-2">Auth Required</span>
                    </h2>

                    <div class="space-y-4">
                        <!-- Get User Loans -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/loans</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get user's loans (active, returned, or all)</p>
                        </div>

                        <!-- Borrow Book -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-post">POST</span>
                                <code class="text-sm">/loans</code>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Borrow a book</p>
                            <details class="text-sm">
                                <summary class="cursor-pointer text-blue-600">Show example</summary>
                                <div class="mt-2 code-block">
curl -X POST "{{ config('app.url') }}/api/v1/loans" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{"book_id": 1}'
                                </div>
                            </details>
                        </div>

                        <!-- Return Book -->
                        <div class="border-l-4 border-purple-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-patch">PATCH</span>
                                <code class="text-sm">/loans/{loan}/return</code>
                            </div>
                            <p class="text-gray-600 text-sm">Return a borrowed book</p>
                        </div>
                    </div>
                </div>

                <!-- Recommendations API -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        ✨ Recommendations API
                    </h2>

                    <div class="space-y-4">
                        <!-- Popular Books -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/recommendations/popular</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get most popular books</p>
                        </div>

                        <!-- Trending Books -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/recommendations/trending</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get trending books (growing popularity)</p>
                        </div>

                        <!-- Personal Recommendations -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/recommendations/personal</code>
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">Auth Required</span>
                            </div>
                            <p class="text-gray-600 text-sm">Get personalized recommendations based on user history</p>
                        </div>

                        <!-- Similar Books -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/recommendations/similar/{bookId}</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get books similar to a specific book</p>
                        </div>
                    </div>
                </div>

                <!-- Notifications API -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        🔔 Notifications API
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded ml-2">Auth Required</span>
                    </h2>

                    <div class="space-y-4">
                        <!-- Get Notifications -->
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-get">GET</span>
                                <code class="text-sm">/notifications</code>
                            </div>
                            <p class="text-gray-600 text-sm">Get user notifications with pagination</p>
                        </div>

                        <!-- Mark as Read -->
                        <div class="border-l-4 border-purple-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-patch">PATCH</span>
                                <code class="text-sm">/notifications/{id}/read</code>
                            </div>
                            <p class="text-gray-600 text-sm">Mark notification as read</p>
                        </div>

                        <!-- Mark All as Read -->
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="method-post">POST</span>
                                <code class="text-sm">/notifications/mark-all-read</code>
                            </div>
                            <p class="text-gray-600 text-sm">Mark all notifications as read</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
                <h2 class="text-xl font-semibold mb-4">Response Format</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold mb-2">Success Response</h3>
                        <div class="code-block">
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Laravel 11 Guide",
    "author": "John Doe",
    "isbn": "978-123-456-789-0",
    "published_year": 2024,
    "quantity": 5,
    "available_quantity": 3,
    "total_loans": 2
  }
}
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Error Response</h3>
                        <div class="code-block">
{
  "success": false,
  "message": "Book not found",
  "errors": {
    "book_id": ["The selected book is invalid."]
  }
}
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2">Paginated Response</h3>
                        <div class="code-block">
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 73,
    "from": 1,
    "to": 15
  }
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Codes -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-8">
                <h2 class="text-xl font-semibold mb-4">HTTP Status Codes</h2>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm font-mono">200</span>
                            <span class="font-medium">OK</span>
                        </div>
                        <p class="text-gray-600 text-sm">Request successful</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-sm font-mono">201</span>
                            <span class="font-medium">Created</span>
                        </div>
                        <p class="text-gray-600 text-sm">Resource created successfully</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-mono">400</span>
                            <span class="font-medium">Bad Request</span>
                        </div>
                        <p class="text-gray-600 text-sm">Invalid request parameters</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-mono">401</span>
                            <span class="font-medium">Unauthorized</span>
                        </div>
                        <p class="text-gray-600 text-sm">Authentication required</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-mono">403</span>
                            <span class="font-medium">Forbidden</span>
                        </div>
                        <p class="text-gray-600 text-sm">Insufficient permissions</p>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm font-mono">422</span>
                            <span class="font-medium">Validation Error</span>
                        </div>
                        <p class="text-gray-600 text-sm">Request validation failed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>