<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google gagal. Coba lagi.');
        }

        return $this->loginOrRegisterUser($socialUser, 'google');
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
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Facebook gagal. Coba lagi.');
        }

        return $this->loginOrRegisterUser($socialUser, 'facebook');
    }

    /**
     * Cari atau buat user, lalu login
     */
    private function loginOrRegisterUser($socialUser, string $provider)
    {
        // Cek apakah user sudah ada berdasarkan social_id + provider
        $user = User::where('social_provider', $provider)
                    ->where('social_id', $socialUser->getId())
                    ->first();

        if (!$user) {
            // Coba cari user berdasarkan email (mungkin sudah daftar manual)
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Update social info ke akun yang sudah ada
                $user->update([
                    'social_id'       => $socialUser->getId(),
                    'social_provider' => $provider,
                    'avatar'          => $socialUser->getAvatar(),
                ]);
            } else {
                // Buat akun baru
                $user = User::create([
                    'name'            => $socialUser->getName(),
                    'email'           => $socialUser->getEmail(),
                    'password'        => Hash::make(Str::random(24)), // password random, user login via social
                    'social_id'       => $socialUser->getId(),
                    'social_provider' => $provider,
                    'avatar'          => $socialUser->getAvatar(),
                    'email_verified_at' => now(), // anggap sudah terverifikasi via social
                ]);
            }
        }

        Auth::login($user, true); // remember = true

        // Redirect berdasarkan role
        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}