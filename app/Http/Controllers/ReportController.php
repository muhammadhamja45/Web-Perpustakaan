<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Get date range filter
        $startDate = request('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));

        // Basic statistics
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::where('role', 'student')->count(),
            'active_loans' => Loan::whereNull('returned_date')->count(),
            'returned_loans' => Loan::whereNotNull('returned_date')->count(),
            'overdue_loans' => Loan::whereNull('returned_date')
                ->where('due_date', '<', now())
                ->count(),
        ];

        // Loan trends for the selected period
        $loanTrends = Loan::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Most borrowed books
        $popularBooks = Book::withCount(['loans' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->having('loans_count', '>', 0)
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->get();

        // Most active users
        $activeUsers = User::where('role', 'student')
            ->withCount(['loans' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->having('loans_count', '>', 0)
            ->orderBy('loans_count', 'desc')
            ->limit(10)
            ->get();

        // Monthly report data
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'loans' => Loan::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'returns' => Loan::whereBetween('returned_date', [$monthStart, $monthEnd])
                    ->whereNotNull('returned_date')->count(),
                'new_books' => Book::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'new_users' => User::where('role', 'student')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        // Category analysis
        $categoryStats = Book::selectRaw('category, COUNT(*) as total_books,
                COUNT(CASE WHEN id IN (SELECT DISTINCT book_id FROM loans WHERE returned_date IS NULL) THEN 1 END) as borrowed_books')
            ->groupBy('category')
            ->get();

        // Overdue analysis
        $overdueDetails = Loan::with(['book', 'user'])
            ->whereNull('returned_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();

        return view('admin.reports.index', compact(
            'stats', 'loanTrends', 'popularBooks', 'activeUsers',
            'monthlyData', 'categoryStats', 'overdueDetails',
            'startDate', 'endDate'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'loans');
        $format = $request->get('format', 'csv');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        switch ($type) {
            case 'loans':
                return $this->exportLoans($format, $startDate, $endDate);
            case 'books':
                return $this->exportBooks($format);
            case 'users':
                return $this->exportUsers($format);
            case 'overdue':
                return $this->exportOverdue($format);
            default:
                abort(404);
        }
    }

    private function exportLoans($format, $startDate, $endDate)
    {
        $loans = Loan::with(['book', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->generateCSV($loans->map(function ($loan) {
                return [
                    'ID' => $loan->id,
                    'Nama Peminjam' => $loan->user->name,
                    'Email' => $loan->user->email,
                    'Judul Buku' => $loan->book->title,
                    'Penulis' => $loan->book->author,
                    'Tanggal Pinjam' => $loan->created_at->format('Y-m-d H:i:s'),
                    'Tanggal Jatuh Tempo' => $loan->due_date->format('Y-m-d'),
                    'Tanggal Kembali' => $loan->returned_date ? $loan->returned_date->format('Y-m-d H:i:s') : 'Belum dikembalikan',
                    'Status' => $loan->returned_date ? 'Dikembalikan' : ($loan->due_date < now() ? 'Terlambat' : 'Dipinjam'),
                ];
            }), 'laporan_peminjaman_' . $startDate . '_to_' . $endDate . '.csv');
        }

        return response()->json(['error' => 'Format not supported'], 400);
    }

    private function exportBooks($format)
    {
        $books = Book::withCount('loans')->orderBy('title')->get();

        if ($format === 'csv') {
            return $this->generateCSV($books->map(function ($book) {
                return [
                    'ID' => $book->id,
                    'Judul' => $book->title,
                    'Penulis' => $book->author,
                    'ISBN' => $book->isbn,
                    'Kategori' => $book->category,
                    'Tahun Terbit' => $book->published_year,
                    'Total Dipinjam' => $book->loans_count,
                    'Status' => $book->is_available ? 'Tersedia' : 'Tidak Tersedia',
                    'Tanggal Ditambahkan' => $book->created_at->format('Y-m-d H:i:s'),
                ];
            }), 'laporan_buku_' . now()->format('Y-m-d') . '.csv');
        }

        return response()->json(['error' => 'Format not supported'], 400);
    }

    private function exportUsers($format)
    {
        $users = User::where('role', 'student')
            ->withCount('loans')
            ->orderBy('name')
            ->get();

        if ($format === 'csv') {
            return $this->generateCSV($users->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'Nama' => $user->name,
                    'Email' => $user->email,
                    'Total Peminjaman' => $user->loans_count,
                    'Status' => $user->is_approved ? 'Aktif' : 'Menunggu Persetujuan',
                    'Tanggal Daftar' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            }), 'laporan_anggota_' . now()->format('Y-m-d') . '.csv');
        }

        return response()->json(['error' => 'Format not supported'], 400);
    }

    private function exportOverdue($format)
    {
        $overdueLoans = Loan::with(['book', 'user'])
            ->whereNull('returned_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();

        if ($format === 'csv') {
            return $this->generateCSV($overdueLoans->map(function ($loan) {
                $daysOverdue = now()->diffInDays($loan->due_date);
                return [
                    'ID' => $loan->id,
                    'Nama Peminjam' => $loan->user->name,
                    'Email' => $loan->user->email,
                    'Judul Buku' => $loan->book->title,
                    'Tanggal Pinjam' => $loan->created_at->format('Y-m-d'),
                    'Tanggal Jatuh Tempo' => $loan->due_date->format('Y-m-d'),
                    'Hari Terlambat' => $daysOverdue,
                    'Status' => 'Terlambat',
                ];
            }), 'laporan_terlambat_' . now()->format('Y-m-d') . '.csv');
        }

        return response()->json(['error' => 'Format not supported'], 400);
    }

    private function generateCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add headers
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
            }

            // Add data
            foreach ($data as $row) {
                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function summary()
    {
        // Quick summary for dashboard widget
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');

        $summary = [
            'today_loans' => Loan::whereDate('created_at', $today)->count(),
            'today_returns' => Loan::whereDate('returned_date', $today)->count(),
            'month_loans' => Loan::where('created_at', 'like', $thisMonth . '%')->count(),
            'month_returns' => Loan::where('returned_date', 'like', $thisMonth . '%')->count(),
            'total_overdue' => Loan::whereNull('returned_date')
                ->where('due_date', '<', now())
                ->count(),
        ];

        return response()->json($summary);
    }
}