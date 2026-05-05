<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Step 1 & 2: Send OTP via WA or Email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required', // Email atau No HP
            'method' => 'required|in:whatsapp,email',
        ]);

        // Cek user (Sesuaikan 'phone_number' jadi 'phone')
        $user = User::where('email', $request->identifier)
                    ->orWhere('phone', $request->identifier)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        $token = Str::random(64);

        // Simpan ke tabel otp_codes menggunakan Query Builder
        DB::table('otp_codes')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'otp' => Hash::make($otp),
                'token' => $token,
                'send_via' => $request->method,
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => Carbon::now(),
            ]
        );

        // Kirim OTP
        if ($request->method == 'whatsapp') {
            // Pastikan user punya nomor HP
            if (!$user->phone) {
                return response()->json(['message' => 'Nomor WhatsApp tidak terdaftar.'], 400);
            }
            return $this->sendViaWhatsapp($user->phone, $otp);
        } else {
            return $this->sendViaEmail($user->email, $otp);
        }
    }

    /**
     * Step 3: Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->identifier)
                    ->orWhere('phone', $request->identifier)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $otpData = DB::table('otp_codes')->where('user_id', $user->id)->first();

        if (!$otpData || Carbon::now()->isAfter($otpData->expires_at)) {
            return response()->json(['message' => 'Kode OTP kadaluarsa atau tidak ditemukan.'], 400);
        }

        if (!Hash::check($request->otp, $otpData->otp)) {
            return response()->json(['message' => 'Kode OTP salah.'], 400);
        }

        return response()->json([
            'message' => 'OTP Valid. Silakan reset sandi Anda.',
            'token' => $otpData->token
        ]);
    }

    /**
     * Step 4: Reset Password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $otpData = DB::table('otp_codes')->where('token', $request->token)->first();

        if (!$otpData) {
            return response()->json(['message' => 'Token tidak valid.'], 400);
        }

        // Update password user
        User::where('id', $otpData->user_id)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus OTP setelah berhasil digunakan
        DB::table('otp_codes')->where('token', $request->token)->delete();

        return response()->json(['message' => 'Kata sandi berhasil diperbarui.']);
    }

    // Helper: Kirim WA via Fonnte
    private function sendViaWhatsapp($target, $otp)
    {
        // Gunakan config services yang sudah kita buat tadi
        $token = config('services.fonnte.token');
        
        $response = Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => "Kode OTP Lupa Sandi BKK SMKN 1 Garut Anda adalah: $otp. Jangan berikan kode ini kepada siapapun.",
        ]);

        return response()->json(['message' => 'OTP berhasil dikirim ke WhatsApp.']);
    }

    // Helper: Kirim Email
    private function sendViaEmail($email, $otp)
    {
        Mail::raw("Kode OTP Lupa Sandi Anda adalah: $otp", function ($message) use ($email) {
            $message->to($email)->subject('OTP Lupa Sandi BKK SMKN 1 Garut');
        });

        return response()->json(['message' => 'OTP berhasil dikirim ke Email.']);
    }
}