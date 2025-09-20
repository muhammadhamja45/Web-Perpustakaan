<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TwoFactorToken extends Model
{
    protected $fillable = [
        'email',
        'token',
        'type',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    public static function generateToken($email, $type)
    {
        // Delete existing tokens for this email and type
        self::where('email', $email)
            ->where('type', $type)
            ->delete();

        // Generate new 6-digit token
        $token = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Create new token
        $twoFactorToken = self::create([
            'email' => $email,
            'token' => $token,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(10), // Token expires in 10 minutes
            'used' => false
        ]);

        // Send email
        self::sendTokenEmail($email, $token, $type);

        return $twoFactorToken;
    }

    public static function verifyToken($email, $token, $type)
    {
        $tokenRecord = self::where('email', $email)
            ->where('token', $token)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($tokenRecord) {
            $tokenRecord->update(['used' => true]);
            return true;
        }

        return false;
    }

    private static function sendTokenEmail($email, $token, $type)
    {
        $subject = $type === 'login' ? 'Kode Verifikasi Login' : 'Kode Verifikasi Registrasi';
        $message = $type === 'login'
            ? "Kode verifikasi untuk login Anda: {$token}\n\nKode ini berlaku selama 10 menit."
            : "Kode verifikasi untuk menyelesaikan registrasi: {$token}\n\nKode ini berlaku selama 10 menit.";

        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)->subject($subject);
        });
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isUsed()
    {
        return $this->used;
    }
}
