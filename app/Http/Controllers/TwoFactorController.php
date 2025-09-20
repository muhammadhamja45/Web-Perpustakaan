<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TwoFactorToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->session()->get('2fa_email');
        $type = $request->session()->get('2fa_type');

        if (!$email || !$type) {
            return redirect()->route('login')->withErrors('Sesi verifikasi tidak valid.');
        }

        return view('auth.two-factor-verify', compact('email', 'type'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6',
        ]);

        $email = $request->session()->get('2fa_email');
        $type = $request->session()->get('2fa_type');
        $userData = $request->session()->get('2fa_user_data');

        if (!$email || !$type) {
            return redirect()->route('login')->withErrors('Sesi verifikasi tidak valid.');
        }

        if (!TwoFactorToken::verifyToken($email, $request->token, $type)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode verifikasi tidak valid atau telah kadaluarsa.',
                    'errors' => [
                        'token' => ['Kode verifikasi tidak valid atau telah kadaluarsa.']
                    ]
                ], 422);
            }
            throw ValidationException::withMessages([
                'token' => ['Kode verifikasi tidak valid atau telah kadaluarsa.'],
            ]);
        }

        if ($type === 'register') {
            // Complete registration
            $user = User::create([
                'name' => $userData['name'],
                'email' => $email,
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'],
                'is_approved' => $userData['role'] === 'student' ? true : false,
                'email_verified_at' => now(),
            ]);

            // Clear session data
            $request->session()->forget(['2fa_email', '2fa_type', '2fa_user_data']);

            if ($userData['role'] === 'admin') {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Registrasi berhasil! Akun admin Anda menunggu persetujuan dari admin lain.',
                        'redirect' => route('login')
                    ]);
                }
                return redirect()->route('login')->with('status',
                    'Registrasi berhasil! Akun admin Anda menunggu persetujuan dari admin lain.');
            } else {
                Auth::login($user);
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Registrasi berhasil! Selamat datang.',
                        'redirect' => route('dashboard')
                    ]);
                }
                return redirect()->intended(route('dashboard'));
            }
        } else {
            // Complete login
            $user = User::where('email', $email)->first();

            // Clear session data
            $request->session()->forget(['2fa_email', '2fa_type']);

            Auth::login($user);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil! Selamat datang.',
                    'redirect' => route('dashboard')
                ]);
            }
            return redirect()->intended(route('dashboard'));
        }
    }

    public function resend(Request $request)
    {
        $email = $request->session()->get('2fa_email');
        $type = $request->session()->get('2fa_type');

        if (!$email || !$type) {
            return response()->json(['error' => 'Sesi verifikasi tidak valid.'], 400);
        }

        try {
            TwoFactorToken::generateToken($email, $type);
            return response()->json(['message' => 'Kode verifikasi baru telah dikirim.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengirim kode verifikasi.'], 500);
        }
    }
}