<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookLoanNotification;
use App\Models\Loan;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-loan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test book loan notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing book loan notification email...');

        // Get the latest loan with relationships
        $loan = Loan::with(['book', 'user'])->latest()->first();

        if (!$loan) {
            $this->error('No loans found in database to test with.');
            return;
        }

        try {
            Mail::to($loan->user->email)->send(new BookLoanNotification($loan));
            $this->info("Email sent successfully to: {$loan->user->email}");
            $this->info("Book: {$loan->book->title}");
        } catch (\Exception $e) {
            $this->error("Failed to send email: {$e->getMessage()}");
        }
    }
}
