<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\LoanApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\RecommendationApiController;
use App\Http\Controllers\Api\SearchApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Books API
    Route::get('/books', [BookApiController::class, 'index']);
    Route::get('/books/search', [SearchApiController::class, 'searchBooks']);
    Route::get('/books/{id}', [BookApiController::class, 'show']);
    Route::get('/books/category/{category}', [BookApiController::class, 'byCategory']);

    // Advanced Search API
    Route::post('/search/advanced', [SearchApiController::class, 'advancedSearch']);
    Route::get('/search/suggestions', [SearchApiController::class, 'getSuggestions']);

    // Book Recommendations
    Route::get('/recommendations/popular', [RecommendationApiController::class, 'popular']);
    Route::get('/recommendations/trending', [RecommendationApiController::class, 'trending']);
});

// Protected API routes (authentication required)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // User specific routes
    Route::get('/user/profile', function (Request $request) {
        return response()->json($request->user());
    });

    // Loans API
    Route::get('/loans', [LoanApiController::class, 'index']);
    Route::post('/loans', [LoanApiController::class, 'store']);
    Route::patch('/loans/{loan}/return', [LoanApiController::class, 'return']);
    Route::get('/loans/history', [LoanApiController::class, 'history']);
    Route::get('/loans/overdue', [LoanApiController::class, 'overdue']);

    // Notifications API
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [NotificationApiController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationApiController::class, 'destroy']);

    // Personal Recommendations
    Route::get('/recommendations/personal', [RecommendationApiController::class, 'personal']);
    Route::get('/recommendations/similar/{bookId}', [RecommendationApiController::class, 'similar']);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // Books management
        Route::post('/books', [BookApiController::class, 'store']);
        Route::put('/books/{book}', [BookApiController::class, 'update']);
        Route::delete('/books/{book}', [BookApiController::class, 'destroy']);

        // Analytics API
        Route::get('/analytics/dashboard', [BookApiController::class, 'analytics']);
        Route::get('/analytics/reports', [BookApiController::class, 'reports']);
    });
});
