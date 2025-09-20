<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Mail\BookLoanNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoanApiController extends Controller
{
    /**
     * Display user's active loans
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = $request->get('status', 'active'); // active, all, returned

        $query = $user->loans()->with(['book:id,title,author,isbn']);

        switch ($status) {
            case 'active':
                $query->whereNull('returned_date');
                break;
            case 'returned':
                $query->whereNotNull('returned_date');
                break;
            // 'all' - no additional filter
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        // Add additional data for each loan
        $loans->each(function ($loan) {
            $loan->is_overdue = $loan->returned_date === null && $loan->due_date < now();
            $loan->days_remaining = $loan->returned_date === null
                ? now()->diffInDays($loan->due_date, false)
                : null;
            $loan->status = $this->getLoanStatus($loan);
        });

        return response()->json([
            'success' => true,
            'data' => $loans,
            'summary' => [
                'active_loans' => $user->loans()->whereNull('returned_date')->count(),
                'overdue_loans' => $user->loans()
                    ->whereNull('returned_date')
                    ->where('due_date', '<', now())
                    ->count(),
                'total_loans' => $user->loans()->count()
            ]
        ]);
    }

    /**
     * Store a new loan (borrow book)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $book = Book::findOrFail($request->book_id);

        // Business rules validation
        if ($book->available_quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Book is not available for borrowing'
            ], 400);
        }

        if ($user->role === 'student') {
            $activeLoansCount = $user->loans()->whereNull('returned_date')->count();
            if ($activeLoansCount >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum loan limit reached (5 books)'
                ], 400);
            }
        }

        // Check if user already has this book borrowed
        $existingLoan = $user->loans()
            ->where('book_id', $book->id)
            ->whereNull('returned_date')
            ->first();

        if ($existingLoan) {
            return response()->json([
                'success' => false,
                'message' => 'You have already borrowed this book'
            ], 400);
        }

        try {
            DB::transaction(function () use ($user, $book, &$loan) {
                // Create loan record
                $loan = Loan::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'borrowed_date' => now(),
                    'due_date' => now()->addDays(14)
                ]);

                // Update book availability
                $book->decrement('available_quantity');

                // Send email notification
                Mail::to($user->email)->queue(new BookLoanNotification($loan));
            });

            $loan->load('book:id,title,author,isbn');
            $loan->is_overdue = false;
            $loan->days_remaining = 14;
            $loan->status = 'active';

            return response()->json([
                'success' => true,
                'message' => 'Book borrowed successfully',
                'data' => $loan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process loan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return a borrowed book
     */
    public function return(Request $request, Loan $loan): JsonResponse
    {
        $user = $request->user();

        // Authorization check
        if ($loan->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to return this book'
            ], 403);
        }

        // Check if already returned
        if ($loan->returned_date) {
            return response()->json([
                'success' => false,
                'message' => 'Book has already been returned'
            ], 400);
        }

        try {
            DB::transaction(function () use ($loan) {
                // Update loan record
                $loan->update(['returned_date' => now()]);

                // Update book availability
                $loan->book->increment('available_quantity');
            });

            $loan->load('book:id,title,author,isbn');
            $loan->status = 'returned';

            return response()->json([
                'success' => true,
                'message' => 'Book returned successfully',
                'data' => $loan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to return book: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get loan history
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 20);

        $loans = $user->loans()
            ->with(['book:id,title,author,isbn'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add status to each loan
        $loans->getCollection()->each(function ($loan) {
            $loan->status = $this->getLoanStatus($loan);
            $loan->is_overdue = $loan->returned_date === null && $loan->due_date < now();
        });

        return response()->json([
            'success' => true,
            'data' => $loans->items(),
            'pagination' => [
                'current_page' => $loans->currentPage(),
                'last_page' => $loans->lastPage(),
                'per_page' => $loans->perPage(),
                'total' => $loans->total()
            ]
        ]);
    }

    /**
     * Get overdue loans
     */
    public function overdue(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Admin can see all overdue loans
            $loans = Loan::with(['user:id,name,email', 'book:id,title,author,isbn'])
                ->whereNull('returned_date')
                ->where('due_date', '<', now())
                ->orderBy('due_date')
                ->get();
        } else {
            // Students see only their overdue loans
            $loans = $user->loans()
                ->with(['book:id,title,author,isbn'])
                ->whereNull('returned_date')
                ->where('due_date', '<', now())
                ->orderBy('due_date')
                ->get();
        }

        $loans->each(function ($loan) {
            $loan->days_overdue = now()->diffInDays($loan->due_date);
            $loan->status = 'overdue';
        });

        return response()->json([
            'success' => true,
            'data' => $loans,
            'count' => $loans->count()
        ]);
    }

    /**
     * Extend loan due date (if allowed)
     */
    public function extend(Request $request, Loan $loan): JsonResponse
    {
        $user = $request->user();

        // Authorization check
        if ($loan->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to extend this loan'
            ], 403);
        }

        // Business rules
        if ($loan->returned_date) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot extend returned book'
            ], 400);
        }

        if ($loan->due_date < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot extend overdue loan'
            ], 400);
        }

        // Check if book has pending reservations (future feature)
        // For now, allow extension up to 7 days

        $newDueDate = $loan->due_date->addDays(7);
        $loan->update(['due_date' => $newDueDate]);

        return response()->json([
            'success' => true,
            'message' => 'Loan extended successfully',
            'data' => [
                'loan_id' => $loan->id,
                'old_due_date' => $loan->due_date->subDays(7)->format('Y-m-d'),
                'new_due_date' => $loan->due_date->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Get loan statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'total_loans' => Loan::count(),
                'active_loans' => Loan::whereNull('returned_date')->count(),
                'overdue_loans' => Loan::whereNull('returned_date')
                    ->where('due_date', '<', now())
                    ->count(),
                'loans_today' => Loan::whereDate('created_at', today())->count(),
                'returns_today' => Loan::whereDate('returned_date', today())->count()
            ];
        } else {
            $stats = [
                'total_loans' => $user->loans()->count(),
                'active_loans' => $user->loans()->whereNull('returned_date')->count(),
                'overdue_loans' => $user->loans()
                    ->whereNull('returned_date')
                    ->where('due_date', '<', now())
                    ->count(),
                'average_loan_duration' => $user->loans()
                    ->whereNotNull('returned_date')
                    ->avg(DB::raw('DATEDIFF(returned_date, borrowed_date)'))
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Helper method to get loan status
     */
    private function getLoanStatus(Loan $loan): string
    {
        if ($loan->returned_date) {
            return 'returned';
        }

        if ($loan->due_date < now()) {
            return 'overdue';
        }

        if ($loan->due_date <= now()->addDays(3)) {
            return 'due_soon';
        }

        return 'active';
    }
}