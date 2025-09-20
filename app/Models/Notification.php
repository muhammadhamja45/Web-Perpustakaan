<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime'
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Create a loan notification
     */
    public static function createLoanNotification(Loan $loan, string $type = 'loan_created'): self
    {
        $titles = [
            'loan_created' => 'Buku Berhasil Dipinjam',
            'loan_reminder' => 'Pengingat Pengembalian Buku',
            'loan_overdue' => 'Buku Terlambat Dikembalikan',
            'loan_returned' => 'Buku Berhasil Dikembalikan'
        ];

        $messages = [
            'loan_created' => "Anda telah meminjam buku '{$loan->book->title}' hingga " . $loan->due_date->format('d M Y'),
            'loan_reminder' => "Buku '{$loan->book->title}' akan jatuh tempo pada " . $loan->due_date->format('d M Y'),
            'loan_overdue' => "Buku '{$loan->book->title}' sudah terlambat " . now()->diffInDays($loan->due_date) . " hari",
            'loan_returned' => "Buku '{$loan->book->title}' telah berhasil dikembalikan"
        ];

        return self::create([
            'user_id' => $loan->user_id,
            'type' => $type,
            'title' => $titles[$type],
            'message' => $messages[$type],
            'data' => [
                'loan_id' => $loan->id,
                'book_id' => $loan->book_id,
                'book_title' => $loan->book->title,
                'due_date' => $loan->due_date->toDateString(),
                'action_url' => route('dashboard')
            ]
        ]);
    }

    /**
     * Create a book notification
     */
    public static function createBookNotification(User $user, Book $book, string $type = 'new_book'): self
    {
        $titles = [
            'new_book' => 'Buku Baru Tersedia',
            'book_available' => 'Buku Kembali Tersedia'
        ];

        $messages = [
            'new_book' => "Buku baru '{$book->title}' oleh {$book->author} telah ditambahkan ke perpustakaan",
            'book_available' => "Buku '{$book->title}' yang Anda tunggu kini tersedia untuk dipinjam"
        ];

        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $titles[$type],
            'message' => $messages[$type],
            'data' => [
                'book_id' => $book->id,
                'book_title' => $book->title,
                'book_author' => $book->author,
                'action_url' => route('books.show', $book->id)
            ]
        ]);
    }
}