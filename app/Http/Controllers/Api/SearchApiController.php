<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchApiController extends Controller
{
    /**
     * Basic book search
     */
    public function searchBooks(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 20);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        $books = Book::where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('author', 'LIKE', "%{$query}%")
                  ->orWhere('isbn', 'LIKE', "%{$query}%");
            })
            ->withCount('loans as total_loans')
            ->orderByRaw("
                CASE
                    WHEN title LIKE '{$query}%' THEN 1
                    WHEN author LIKE '{$query}%' THEN 2
                    WHEN title LIKE '%{$query}%' THEN 3
                    WHEN author LIKE '%{$query}%' THEN 4
                    ELSE 5
                END
            ")
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'count' => $books->count(),
            'data' => $books
        ]);
    }

    /**
     * Advanced search with multiple filters
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'year_from' => 'nullable|integer|min:1000',
            'year_to' => 'nullable|integer|max:' . date('Y'),
            'available_only' => 'nullable|boolean',
            'sort_by' => 'nullable|in:title,author,published_year,total_loans,created_at',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Book::select([
            'id', 'title', 'author', 'isbn', 'published_year',
            'quantity', 'available_quantity', 'category', 'created_at'
        ])->withCount('loans as total_loans');

        // Apply filters
        if (!empty($filters['title'])) {
            $query->where('title', 'LIKE', "%{$filters['title']}%");
        }

        if (!empty($filters['author'])) {
            $query->where('author', 'LIKE', "%{$filters['author']}%");
        }

        if (!empty($filters['isbn'])) {
            $query->where('isbn', 'LIKE', "%{$filters['isbn']}%");
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['year_from'])) {
            $query->where('published_year', '>=', $filters['year_from']);
        }

        if (!empty($filters['year_to'])) {
            $query->where('published_year', '<=', $filters['year_to']);
        }

        if ($filters['available_only'] ?? false) {
            $query->where('available_quantity', '>', 0);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'title';
        $sortOrder = $filters['sort_order'] ?? 'asc';

        if ($sortBy === 'total_loans') {
            $query->orderBy('loans_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $filters['per_page'] ?? 15;
        $results = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'filters' => $filters,
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
            ]
        ]);
    }

    /**
     * Get search suggestions for autocomplete
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, title, author, category

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        $suggestions = collect();

        switch ($type) {
            case 'title':
                $suggestions = Book::where('title', 'LIKE', "{$query}%")
                    ->distinct()
                    ->limit(10)
                    ->pluck('title');
                break;

            case 'author':
                $suggestions = Book::where('author', 'LIKE', "{$query}%")
                    ->distinct()
                    ->limit(10)
                    ->pluck('author');
                break;

            case 'category':
                $suggestions = Book::where('category', 'LIKE', "{$query}%")
                    ->whereNotNull('category')
                    ->distinct()
                    ->limit(10)
                    ->pluck('category');
                break;

            default:
                // Combined suggestions
                $titleSuggestions = Book::where('title', 'LIKE', "{$query}%")
                    ->limit(5)
                    ->pluck('title')
                    ->map(function($title) {
                        return ['value' => $title, 'type' => 'title'];
                    });

                $authorSuggestions = Book::where('author', 'LIKE', "{$query}%")
                    ->limit(5)
                    ->pluck('author')
                    ->map(function($author) {
                        return ['value' => $author, 'type' => 'author'];
                    });

                $suggestions = $titleSuggestions->concat($authorSuggestions);
                break;
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'type' => $type,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Get popular search terms
     */
    public function getPopularSearches(): JsonResponse
    {
        // In a real application, you would track search queries
        // For now, we'll return popular book titles and authors
        $popularTitles = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->pluck('title');

        $popularAuthors = Book::select('author')
            ->withCount('loans')
            ->groupBy('author')
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->pluck('author');

        return response()->json([
            'success' => true,
            'data' => [
                'popular_titles' => $popularTitles,
                'popular_authors' => $popularAuthors
            ]
        ]);
    }

    /**
     * Search with filters and facets
     */
    public function facetedSearch(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        // Get facets (available filters)
        $facets = [
            'categories' => Book::whereNotNull('category')
                ->select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),

            'years' => Book::whereNotNull('published_year')
                ->select('published_year', DB::raw('COUNT(*) as count'))
                ->groupBy('published_year')
                ->orderBy('published_year', 'desc')
                ->limit(20)
                ->get(),

            'authors' => Book::select('author', DB::raw('COUNT(*) as count'))
                ->groupBy('author')
                ->orderBy('count', 'desc')
                ->limit(20)
                ->get(),

            'availability' => [
                ['value' => 'available', 'label' => 'Available', 'count' => Book::where('available_quantity', '>', 0)->count()],
                ['value' => 'all', 'label' => 'All Books', 'count' => Book::count()]
            ]
        ];

        // Perform search if query provided
        $books = collect();
        if (!empty($query)) {
            $books = Book::where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('author', 'LIKE', "%{$query}%");
                })
                ->withCount('loans as total_loans')
                ->limit(20)
                ->get();
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'facets' => $facets,
            'results' => $books
        ]);
    }
}