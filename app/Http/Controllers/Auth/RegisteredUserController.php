<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TwoFactorToken;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,student'],
        ]);

        // Generate 2FA token and send to email
        try {
            TwoFactorToken::generateToken($request->email, 'register');

            // Store registration data in session for verification
            $request->session()->put('2fa_email', $request->email);
            $request->session()->put('2fa_type', 'register');
            $request->session()->put('2fa_user_data', [
                'name' => $request->name,
                'password' => $request->password,
                'role' => $request->role,
            ]);
            $request->session()->save(); // Force save session

            // Check if request expects JSON response (for AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode verifikasi telah dikirim ke email Anda untuk menyelesaikan registrasi.',
                    'email' => $request->email
                ]);
            }

            return redirect()->route('2fa.verify')
                ->with('status', 'Kode verifikasi telah dikirim ke email Anda untuk menyelesaikan registrasi.');
        } catch (\Exception $e) {
            // Check if request expects JSON response (for AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim kode verifikasi: ' . $e->getMessage(),
                    'errors' => [
                        'email' => ['Gagal mengirim kode verifikasi: ' . $e->getMessage()]
                    ]
                ], 422);
            }

            return back()->withErrors([
                'email' => 'Gagal mengirim kode verifikasi: ' . $e->getMessage(),
            ])->withInput();
        }
    }
}
