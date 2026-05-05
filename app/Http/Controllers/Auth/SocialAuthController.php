<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite; // Pastikan ini ada
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect ke halaman login Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback setelah login Google berhasil
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            return $this->loginOrRegisterUser($socialUser, 'google');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google gagal: ' . $e->getMessage());
        }
    }

    /**
     * Redirect ke halaman login Facebook
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Callback setelah login Facebook berhasil
     */
    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            return $this->loginOrRegisterUser($socialUser, 'facebook');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Facebook gagal: ' . $e->getMessage());
        }
    }

    /**
     * Cari atau buat user, lalu login
     */
    private function loginOrRegisterUser($socialUser, string $provider)
    {
        // Validasi jika email tidak ditemukan dari provider
        if (!$socialUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Email tidak ditemukan dari akun ' . ucfirst($provider));
        }

        // 1. Cek apakah user sudah pernah login pakai provider ini sebelumnya
        $user = User::where('social_provider', $provider)
                    ->where('social_id', $socialUser->getId())
                    ->first();

        if (!$user) {
            // 2. Cek apakah email sudah terdaftar di sistem (mungkin daftar manual)
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Update info social ke akun yang sudah ada
                $user->update([
                    'social_id'       => $socialUser->getId(),
                    'social_provider' => $provider,
                    'avatar'          => $socialUser->getAvatar(),
                ]);
            } else {
                // 3. Jika benar-benar belum ada, buat user baru
                $user = User::create([
                    'name'              => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email'             => $socialUser->getEmail(),
                    'password'          => Hash::make(Str::random(24)),
                    'social_id'         => $socialUser->getId(),
                    'social_provider'   => $provider,
                    'avatar'            => $socialUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }
        }

        // Login user
        Auth::login($user, true);

        // Redirect berdasarkan role (Pastikan method hasRole tersedia di model User)
        // Jika kamu menggunakan Spatie Permission, gunakan hasRole. 
        // Jika pakai kolom manual, sesuaikan logikanya.
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Default redirect ke student dashboard
        return redirect()->intended(route('student.home'));
    }
}