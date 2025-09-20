<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RecommendationApiController extends Controller
{
    /**
     * Get popular books (most borrowed)
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $timeframe = $request->get('timeframe', 'all'); // all, month, week

        $query = Book::withCount(['loans' => function($q) use ($timeframe) {
            switch ($timeframe) {
                case 'month':
                    $q->where('created_at', '>=', now()->subMonth());
                    break;
                case 'week':
                    $q->where('created_at', '>=', now()->subWeek());
                    break;
                // 'all' - no date filter
            }
        }]);

        $books = $query->having('loans_count', '>', 0)
            ->orderBy('loans_count', 'desc')
            ->limit($limit)
            ->get();

        $books->each(function ($book) {
            $book->popularity_score = $book->loans_count;
            $book->recommendation_reason = "Dipinjam {$book->loans_count} kali";
        });

        return response()->json([
            'success' => true,
            'type' => 'popular',
            'timeframe' => $timeframe,
            'data' => $books
        ]);
    }

    /**
     * Get trending books (recently popular)
     */
    public function trending(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);

        // Books with increasing popularity in last 2 weeks vs previous period
        $recentLoans = Book::withCount(['loans as recent_loans' => function($q) {
                $q->where('created_at', '>=', now()->subWeeks(2));
            }])
            ->withCount(['loans as previous_loans' => function($q) {
                $q->whereBetween('created_at', [now()->subWeeks(4), now()->subWeeks(2)]);
            }])
            ->having('recent_loans', '>', 0)
            ->get()
            ->map(function ($book) {
                $growth = $book->previous_loans > 0
                    ? (($book->recent_loans - $book->previous_loans) / $book->previous_loans) * 100
                    : ($book->recent_loans > 0 ? 100 : 0);

                $book->growth_rate = round($growth, 1);
                $book->trending_score = $book->recent_loans * (1 + $growth / 100);
                $book->recommendation_reason = $book->growth_rate > 0
                    ? "Trending naik {$book->growth_rate}%"
                    : "Buku populer terbaru";

                return $book;
            })
            ->sortByDesc('trending_score')
            ->take($limit)
            ->values();

        return response()->json([
            'success' => true,
            'type' => 'trending',
            'data' => $recentLoans
        ]);
    }

    /**
     * Get personalized recommendations for authenticated user
     */
    public function personal(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        // Get user's borrowing history
        $userBooks = $user->loans()->pluck('book_id')->toArray();

        if (empty($userBooks)) {
            // New user - return popular books
            return $this->popular($request);
        }

        // Find authors user has borrowed from
        $userAuthors = Book::whereIn('id', $userBooks)->pluck('author')->unique()->toArray();

        // Find categories user has borrowed from (if implemented)
        $userCategories = Book::whereIn('id', $userBooks)
            ->whereNotNull('category')
            ->pluck('category')
            ->unique()
            ->toArray();

        // Collaborative filtering: find similar users
        $similarUsers = $this->findSimilarUsers($user, $userBooks);

        $recommendations = collect();

        // 1. Books by same authors (40% weight)
        if (!empty($userAuthors)) {
            $authorBooks = Book::whereIn('author', $userAuthors)
                ->whereNotIn('id', $userBooks)
                ->where('available_quantity', '>', 0)
                ->withCount('loans')
                ->limit(ceil($limit * 0.4))
                ->get();

            $authorBooks->each(function ($book) {
                $book->recommendation_reason = "Dari penulis yang pernah Anda baca";
                $book->recommendation_score = 0.4;
            });

            $recommendations = $recommendations->concat($authorBooks);
        }

        // 2. Books from same categories (30% weight)
        if (!empty($userCategories)) {
            $categoryBooks = Book::whereIn('category', $userCategories)
                ->whereNotIn('id', $userBooks)
                ->where('available_quantity', '>', 0)
                ->withCount('loans')
                ->limit(ceil($limit * 0.3))
                ->get();

            $categoryBooks->each(function ($book) {
                $book->recommendation_reason = "Kategori yang Anda sukai";
                $book->recommendation_score = 0.3;
            });

            $recommendations = $recommendations->concat($categoryBooks);
        }

        // 3. Books borrowed by similar users (30% weight)
        if (!empty($similarUsers)) {
            $collaborativeBooks = $this->getCollaborativeRecommendations($similarUsers, $userBooks, ceil($limit * 0.3));
            $recommendations = $recommendations->concat($collaborativeBooks);
        }

        // Remove duplicates and sort by score
        $uniqueRecommendations = $recommendations->unique('id')
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();

        return response()->json([
            'success' => true,
            'type' => 'personal',
            'user_profile' => [
                'books_borrowed' => count($userBooks),
                'favorite_authors' => array_slice($userAuthors, 0, 3),
                'favorite_categories' => array_slice($userCategories, 0, 3)
            ],
            'data' => $uniqueRecommendations
        ]);
    }

    /**
     * Get books similar to a specific book
     */
    public function similar(Request $request, int $bookId): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        $similarBooks = collect();

        // 1. Same author (50% weight)
        $sameAuthor = Book::where('author', $book->author)
            ->where('id', '!=', $book->id)
            ->where('available_quantity', '>', 0)
            ->withCount('loans')
            ->limit(ceil($limit * 0.5))
            ->get();

        $sameAuthor->each(function ($item) use ($book) {
            $item->recommendation_reason = "Dari penulis yang sama: {$book->author}";
            $item->similarity_score = 0.5;
        });

        $similarBooks = $similarBooks->concat($sameAuthor);

        // 2. Same category (30% weight)
        if ($book->category) {
            $sameCategory = Book::where('category', $book->category)
                ->where('id', '!=', $book->id)
                ->where('author', '!=', $book->author) // Exclude same author (already covered)
                ->where('available_quantity', '>', 0)
                ->withCount('loans')
                ->limit(ceil($limit * 0.3))
                ->get();

            $sameCategory->each(function ($item) use ($book) {
                $item->recommendation_reason = "Kategori yang sama: {$book->category}";
                $item->similarity_score = 0.3;
            });

            $similarBooks = $similarBooks->concat($sameCategory);
        }

        // 3. Frequently borrowed together (20% weight)
        $frequentlyTogether = $this->getBooksFrequentlyBorrowedTogether($bookId, ceil($limit * 0.2));
        $similarBooks = $similarBooks->concat($frequentlyTogether);

        $uniqueSimilar = $similarBooks->unique('id')
            ->sortByDesc('similarity_score')
            ->take($limit)
            ->values();

        return response()->json([
            'success' => true,
            'type' => 'similar',
            'reference_book' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'category' => $book->category
            ],
            'data' => $uniqueSimilar
        ]);
    }

    /**
     * Get new arrivals
     */
    public function newArrivals(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $days = $request->get('days', 30);

        $newBooks = Book::where('created_at', '>=', now()->subDays($days))
            ->where('available_quantity', '>', 0)
            ->withCount('loans')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $newBooks->each(function ($book) {
            $book->recommendation_reason = "Buku baru di perpustakaan";
            $book->days_since_added = now()->diffInDays($book->created_at);
        });

        return response()->json([
            'success' => true,
            'type' => 'new_arrivals',
            'timeframe' => "{$days} days",
            'data' => $newBooks
        ]);
    }

    /**
     * Find users with similar borrowing patterns
     */
    private function findSimilarUsers(User $user, array $userBooks): array
    {
        if (empty($userBooks)) {
            return [];
        }

        // Find users who borrowed similar books
        $similarUsers = Loan::whereIn('book_id', $userBooks)
            ->where('user_id', '!=', $user->id)
            ->select('user_id', DB::raw('COUNT(*) as common_books'))
            ->groupBy('user_id')
            ->having('common_books', '>=', 2) // At least 2 books in common
            ->orderBy('common_books', 'desc')
            ->limit(10)
            ->pluck('user_id')
            ->toArray();

        return $similarUsers;
    }

    /**
     * Get recommendations based on similar users' borrowing patterns
     */
    private function getCollaborativeRecommendations(array $similarUsers, array $userBooks, int $limit): \Illuminate\Support\Collection
    {
        $recommendations = Book::whereIn('id', function($query) use ($similarUsers, $userBooks) {
                $query->select('book_id')
                    ->from('loans')
                    ->whereIn('user_id', $similarUsers)
                    ->whereNotIn('book_id', $userBooks);
            })
            ->where('available_quantity', '>', 0)
            ->withCount(['loans' => function($q) use ($similarUsers) {
                $q->whereIn('user_id', $similarUsers);
            }])
            ->orderBy('loans_count', 'desc')
            ->limit($limit)
            ->get();

        $recommendations->each(function ($book) {
            $book->recommendation_reason = "Pembaca dengan minat serupa juga meminjam";
            $book->recommendation_score = 0.3;
        });

        return $recommendations;
    }

    /**
     * Get books frequently borrowed together with the given book
     */
    private function getBooksFrequentlyBorrowedTogether(int $bookId, int $limit): \Illuminate\Support\Collection
    {
        // Find users who borrowed this book
        $usersWhoBorrowedBook = Loan::where('book_id', $bookId)->pluck('user_id')->unique();

        // Find other books these users borrowed
        $frequentlyTogether = Book::whereIn('id', function($query) use ($usersWhoBorrowedBook, $bookId) {
                $query->select('book_id')
                    ->from('loans')
                    ->whereIn('user_id', $usersWhoBorrowedBook)
                    ->where('book_id', '!=', $bookId);
            })
            ->where('available_quantity', '>', 0)
            ->withCount(['loans' => function($q) use ($usersWhoBorrowedBook) {
                $q->whereIn('user_id', $usersWhoBorrowedBook);
            }])
            ->orderBy('loans_count', 'desc')
            ->limit($limit)
            ->get();

        $frequentlyTogether->each(function ($book) {
            $book->recommendation_reason = "Sering dipinjam bersamaan";
            $book->similarity_score = 0.2;
        });

        return $frequentlyTogether;
    }
}