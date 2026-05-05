<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Kirim OTP ke WhatsApp atau Email
     * POST /forgot-password/send-otp
     */
    public function sendOtp(Request $request)
    {
        $contact = $request->input('contact');
        $sendVia = $request->input('send_via', 'whatsapp'); // 'whatsapp' | 'email'

        // Normalisasi nomor HP (hapus karakter non-digit, ubah 08xx -> 628xx)
        $isPhone = preg_match('/^[0-9\+\-\s]+$/', preg_replace('/\s/', '', $contact))
                   && !filter_var($contact, FILTER_VALIDATE_EMAIL);

        // Cari user
        $user = null;
        if ($isPhone) {
            $phone = $this->normalizePhone($contact);
            $user  = User::where('phone', $phone)->orWhere('phone', $contact)->first();
        } else {
            $user = User::where('email', $contact)->first();
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dengan data tersebut tidak ditemukan.'
            ], 404);
        }

        // Hapus OTP lama milik user ini
        OtpCode::where('user_id', $user->id)->delete();

        // Buat OTP 6 digit
        $otp   = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(64); // token untuk identifikasi session

        OtpCode::create([
            'user_id'    => $user->id,
            'token'      => $token,
            'otp'        => Hash::make($otp),
            'send_via'   => $sendVia,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Kirim OTP
        $destination = '';
        if ($sendVia === 'whatsapp' && $isPhone) {
            $phone       = $this->normalizePhone($contact);
            $destination = $this->maskPhone($phone);
            $this->sendWhatsApp($phone, $otp, $user->name);
        } else {
            $destination = $this->maskEmail($user->email);
            $this->sendEmail($user->email, $otp, $user->name);
        }

        return response()->json([
            'success'     => true,
            'token'       => $token,
            'destination' => $destination,
            'message'     => 'Kode OTP berhasil dikirim.'
        ]);
    }

    /**
     * Verifikasi OTP
     * POST /forgot-password/verify-otp
     */
    public function verifyOtp(Request $request)
    {
        $otp   = $request->input('otp');
        $token = $request->input('token');

        $record = OtpCode::where('token', $token)
                         ->where('expires_at', '>', Carbon::now())
                         ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi kode OTP tidak valid atau sudah kadaluarsa.'
            ], 422);
        }

        if (!Hash::check($otp, $record->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah. Periksa kembali.'
            ], 422);
        }

        // Tandai OTP sudah terverifikasi & buat reset token baru
        $resetToken = Str::random(64);
        $record->update([
            'verified'    => true,
            'reset_token' => $resetToken,
            'expires_at'  => Carbon::now()->addMinutes(15), // perpanjang 15 menit untuk reset
        ]);

        return response()->json([
            'success'     => true,
            'reset_token' => $resetToken,
            'message'     => 'Kode OTP valid.'
        ]);
    }

    /**
     * Reset password dengan token
     * POST /forgot-password/reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => 'required|min:6|confirmed',
            'token'                 => 'required|string',
        ]);

        $record = OtpCode::where('reset_token', $request->input('token'))
                         ->where('verified', true)
                         ->where('expires_at', '>', Carbon::now())
                         ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset tidak valid atau sudah kadaluarsa. Ulangi proses dari awal.'
            ], 422);
        }

        $user = User::find($record->user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        // Hapus record OTP setelah berhasil
        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diubah.'
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Normalisasi no HP Indonesia → format internasional 628xxx
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\+]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }
        return $phone;
    }

    private function maskPhone(string $phone): string
    {
        return substr($phone, 0, 4) . '****' . substr($phone, -3);
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        return substr($local, 0, 2) . '***@' . $domain;
    }

    /**
     * Kirim OTP via WhatsApp menggunakan Fonnte API
     * Daftar & dapatkan token di https://fonnte.com
     */
    private function sendWhatsApp(string $phone, string $otp, string $name): void
    {
        $token   = config('services.fonnte.token'); // isi di .env: FONNTE_TOKEN=xxxxx
        $message = "Halo {$name},\n\nKode OTP untuk reset kata sandi BKK SMKN 1 Garut kamu adalah:\n\n*{$otp}*\n\nKode berlaku selama 10 menit.\nJangan berikan kode ini kepada siapa pun.\n\n_BKK SMKN 1 Garut_";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'target'  => $phone,
                'message' => $message,
            ]),
            CURLOPT_HTTPHEADER     => ["Authorization: {$token}"],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Kirim OTP via Email (gunakan Mail facade Laravel)
     */
    private function sendEmail(string $email, string $otp, string $name): void
    {
        Mail::send([], [], function ($message) use ($email, $otp, $name) {
            $message->to($email)
                    ->subject('Kode OTP Reset Kata Sandi - BKK SMKN 1 Garut')
                    ->html("
                        <div style='font-family:Poppins,Arial,sans-serif;max-width:500px;margin:auto;'>
                          <div style='background:#001f3f;padding:24px;border-radius:16px 16px 0 0;text-align:center;'>
                            <h2 style='color:#fff;margin:0;'>BKK SMKN 1 Garut</h2>
                          </div>
                          <div style='background:#f8fafc;padding:32px;border-radius:0 0 16px 16px;text-align:center;'>
                            <p style='color:#334155;'>Halo <strong>{$name}</strong>,</p>
                            <p style='color:#334155;'>Gunakan kode OTP berikut untuk mereset kata sandi kamu:</p>
                            <div style='background:#fff;border:2px dashed #2563eb;border-radius:12px;padding:20px;margin:24px 0;'>
                              <span style='font-size:36px;font-weight:900;letter-spacing:12px;color:#1d4ed8;'>{$otp}</span>
                            </div>
                            <p style='color:#64748b;font-size:13px;'>Kode ini berlaku selama <strong>10 menit</strong>.</p>
                            <p style='color:#64748b;font-size:13px;'>Jangan bagikan kode ini kepada siapa pun.</p>
                          </div>
                        </div>
                    ");
        });
    }
}