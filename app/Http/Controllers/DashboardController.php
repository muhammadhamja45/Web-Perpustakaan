<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        if ($isAdmin) {
            return $this->adminDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    private function adminDashboard()
    {
        // Statistics
        $totalBooks = Book::count();
        $totalLoans = Loan::whereNull('returned_date')->count();
        $totalMembers = User::where('role', 'student')->count();
        $overdueLoans = Loan::whereNull('returned_date')
            ->where('due_date', '<', now())
            ->count();

        // Popular books with real data
        $popularBooks = Book::withCount(['loans' => function ($query) {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        }])
        ->orderBy('loans_count', 'desc')
        ->take(5)
        ->get();

        // Borrowing trends for the last 7 days
        $borrowingTrends = Loan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [now()->subDays(6), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

        // Fill missing dates with 0
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trends[] = [
                'date' => $date,
                'count' => $borrowingTrends->get($date)?->count ?? 0,
                'label' => now()->subDays($i)->format('D')
            ];
        }

        // Recent loans (today's activity)
        $recentLoans = Loan::with(['user', 'book'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pending admin approvals
        $pendingAdmins = User::pendingAdmins()->count();

        // Share pendingAdmins count with all views for sidebar notification
        view()->share('pendingAdmins', $pendingAdmins);

        return view('dashboard', compact(
            'totalBooks',
            'totalLoans',
            'totalMembers',
            'overdueLoans',
            'popularBooks',
            'trends',
            'recentLoans',
            'pendingAdmins'
        ));
    }

    private function studentDashboard()
    {
        $user = Auth::user();

        // Student's current loans
        $currentLoans = Loan::where('user_id', $user->id)
            ->whereNull('returned_date')
            ->with('book')
            ->get();

        // Total books borrowed (history)
        $totalBorrowed = Loan::where('user_id', $user->id)->count();

        return view('dashboard', compact(
            'currentLoans',
            'totalBorrowed'
        ));
    }
}