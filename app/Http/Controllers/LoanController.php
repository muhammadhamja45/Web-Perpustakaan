<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookLoanNotification;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin melihat semua loans
            $loans = Loan::with(['book', 'user'])->whereNull('returned_date')->latest()->get();
        } else {
            // Student hanya melihat loans mereka sendiri
            $loans = Loan::with(['book', 'user'])
                ->where('user_id', $user->id)
                ->whereNull('returned_date')
                ->latest()
                ->get();
        }

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $books = Book::where('available_quantity', '>', 0)->get();
        return view('loans.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
        ]);

        $book = Book::find($validated['book_id']);

        if ($book->available_quantity <= 0) {
            return back()->withErrors(['book_id' => 'Buku ini tidak tersedia untuk dipinjam.']);
        }

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrowed_date' => now(),
            'due_date' => $validated['due_date'],
        ]);

        $book->decrement('available_quantity');

        // Load relationships for email
        $loan->load(['book', 'user']);

        // Send email notification
        try {
            Mail::to($loan->user->email)->send(new BookLoanNotification($loan));
        } catch (\Exception $e) {
            // Log error but don't fail the loan process
            \Log::error('Failed to send loan notification email: ' . $e->getMessage());
        }

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dipinjam! Konfirmasi telah dikirim ke email Anda.');
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->returned_date) {
            return back()->withErrors(['message' => 'Buku ini sudah dikembalikan.']);
        }

        $loan->update([
            'returned_date' => now(),
        ]);

        $loan->book->increment('available_quantity');

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan!');
    }
}
