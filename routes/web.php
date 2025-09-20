<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('loans', App\Http\Controllers\LoanController::class)->only(['index', 'create', 'store']);
    Route::patch('loans/{loan}/return', [App\Http\Controllers\LoanController::class, 'returnBook'])->name('loans.return');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::resource('books', App\Http\Controllers\BookController::class);

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/user-approvals', [App\Http\Controllers\Admin\UserApprovalController::class, 'index'])->name('users.approvals');
            Route::patch('/users/{user}/approve', [App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])->name('users.approve');
            Route::delete('/users/{user}/reject', [App\Http\Controllers\Admin\UserApprovalController::class, 'reject'])->name('users.reject');
            Route::post('/users/bulk-approve', [App\Http\Controllers\Admin\UserApprovalController::class, 'bulkApprove'])->name('users.bulk-approve');

            // Reports routes
            Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/export', [App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
            Route::get('/reports/summary', [App\Http\Controllers\ReportController::class, 'summary'])->name('reports.summary');
        });
    });
});

require __DIR__.'/auth.php';
