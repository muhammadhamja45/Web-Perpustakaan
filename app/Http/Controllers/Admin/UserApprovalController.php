<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{

    /**
     * Display pending admin approvals
     */
    public function index()
    {
        $pendingUsers = User::pendingAdmins()->with('approver')->get();

        return view('admin.user-approvals.index', compact('pendingUsers'));
    }

    /**
     * Approve admin user
     */
    public function approve(User $user)
    {
        if ($user->role !== 'admin' || $user->is_approved) {
            return back()->with('error', 'User tidak dapat disetujui.');
        }

        $user->approve(Auth::user());

        return back()->with('success',
            "Admin {$user->name} berhasil disetujui. Mereka sekarang dapat mengakses fitur admin.");
    }

    /**
     * Reject admin user
     */
    public function reject(User $user)
    {
        if ($user->role !== 'admin' || $user->is_approved) {
            return back()->with('error', 'User tidak dapat ditolak.');
        }

        $user->delete();

        return back()->with('success',
            "Pendaftaran admin {$user->name} berhasil ditolak dan dihapus.");
    }

    /**
     * Bulk approve users
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)
                    ->where('role', 'admin')
                    ->where('is_approved', false)
                    ->get();

        $approved = 0;
        foreach ($users as $user) {
            $user->approve(Auth::user());
            $approved++;
        }

        return back()->with('success',
            "{$approved} admin berhasil disetujui sekaligus.");
    }
}
