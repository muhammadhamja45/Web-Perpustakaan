<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    /**
     * Display a listing of books with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'title');
        $sortOrder = $request->get('sort_order', 'asc');

        $books = Book::select([
                'id', 'title', 'author', 'isbn', 'published_year',
                'quantity', 'available_quantity', 'created_at'
            ])
            ->withCount(['loans as total_loans'])
            ->when($request->get('available_only'), function($query) {
                return $query->where('available_quantity', '>', 0);
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books->items(),
            'pagination' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'from' => $books->firstItem(),
                'to' => $books->lastItem(),
            ]
        ]);
    }

    /**
     * Display the specified book
     */
    public function show(int $id): JsonResponse
    {
        $book = Book::with(['loans' => function($query) {
                $query->whereNull('returned_date')
                      ->with('user:id,name,email');
            }])
            ->withCount([
                'loans as total_loans',
                'loans as active_loans' => function($query) {
                    $query->whereNull('returned_date');
                }
            ])
            ->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Store a newly created book (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'quantity' => 'required|integer|min:1',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'published_year' => $request->published_year,
            'quantity' => $request->quantity,
            'available_quantity' => $request->quantity,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    /**
     * Update the specified book (Admin only)
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|unique:books,isbn,' . $book->id,
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'quantity' => 'sometimes|required|integer|min:1',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle quantity updates
        if ($request->has('quantity')) {
            $currentBorrowed = $book->quantity - $book->available_quantity;
            $newQuantity = $request->quantity;

            if ($newQuantity < $currentBorrowed) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot reduce quantity below currently borrowed books ({$currentBorrowed})"
                ], 400);
            }

            $book->available_quantity = $newQuantity - $currentBorrowed;
        }

        $book->update($request->only([
            'title', 'author', 'isbn', 'published_year',
            'quantity', 'category', 'description'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'data' => $book->fresh()
        ]);
    }

    /**
     * Remove the specified book (Admin only)
     */
    public function destroy(Book $book): JsonResponse
    {
        // Check if book has active loans
        $activeLoans = $book->loans()->whereNull('returned_date')->count();

        if ($activeLoans > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete book with {$activeLoans} active loans"
            ], 400);
        }

        $book->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }

    /**
     * Get books by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $books = Book::where('category', $category)
            ->where('available_quantity', '>', 0)
            ->withCount('loans as total_loans')
            ->orderBy('title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $books,
            'category' => $category
        ]);
    }

    /**
     * Get analytics data for admin dashboard
     */
    public function analytics(): JsonResponse
    {
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available_quantity', '>', 0)->count(),
            'total_loans' => Loan::count(),
            'active_loans' => Loan::whereNull('returned_date')->count(),
            'overdue_loans' => Loan::whereNull('returned_date')
                ->where('due_date', '<', now())
                ->count(),
        ];

        // Monthly loan trends
        $monthlyLoans = Loan::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Popular books
        $popularBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->get(['id', 'title', 'author']);

        // Recent activity
        $recentLoans = Loan::with(['user:id,name', 'book:id,title'])
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'monthly_trends' => $monthlyLoans,
                'popular_books' => $popularBooks,
                'recent_activity' => $recentLoans
            ]
        ]);
    }

    /**
     * Generate reports
     */
    public function reports(Request $request): JsonResponse
    {
        $type = $request->get('type', 'summary');
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        switch ($type) {
            case 'loans':
                $data = Loan::with(['user:id,name,email', 'book:id,title,author'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                break;

            case 'popular':
                $data = Book::withCount(['loans' => function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }])
                    ->having('loans_count', '>', 0)
                    ->orderBy('loans_count', 'desc')
                    ->get();
                break;

            default:
                $data = [
                    'period' => "{$startDate} to {$endDate}",
                    'total_loans' => Loan::whereBetween('created_at', [$startDate, $endDate])->count(),
                    'unique_borrowers' => Loan::whereBetween('created_at', [$startDate, $endDate])
                        ->distinct('user_id')->count(),
                    'most_popular_book' => Book::withCount(['loans' => function($query) use ($startDate, $endDate) {
                            $query->whereBetween('created_at', [$startDate, $endDate]);
                        }])
                        ->orderBy('loans_count', 'desc')
                        ->first(['title', 'author'])
                ];
        }

        return response()->json([
            'success' => true,
            'report_type' => $type,
            'period' => "{$startDate} to {$endDate}",
            'data' => $data
        ]);
    }
}