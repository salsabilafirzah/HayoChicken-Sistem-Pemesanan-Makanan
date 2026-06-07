<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ─── Web Views ───────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    // ─── Login ───────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Kredensial tidak valid.'], 401);
            }
            return back()->with('error', 'Kredensial tidak valid.');
        }

        // Jika request dari web browser (bukan API), gunakan session login standar Laravel
        if (!$request->wantsJson()) {
            Auth::guard('web')->login($user, true);
            $request->session()->regenerate();
            $request->session()->put('logged_in_at', now()->toDateTimeString());
            $request->session()->save();

            // Debug header (opsional tapi membantu)
            return $user->role === 'SELLER' 
                ? redirect()->intended(route('seller.dashboard'))
                : redirect()->intended(route('home'));
        }

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        // Generate Refresh Token
        $rawRefreshToken = \Illuminate\Support\Str::random(64);
        \App\Models\RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $rawRefreshToken),
            'expires_at' => now()->addDays(7),
        ]);

        return response()->json([
            'access_token' => $token,
            'refresh_token' => $rawRefreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => [
                'name' => $user->name,
                'role' => $user->role,
                'email' => $user->email
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|regex:/^\+62[0-9]{8,13}$/',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => 'CUSTOMER',
        ]);

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'access_token' => $token,
            'token_type' => 'bearer'
        ], 201);
    }

    public function refresh(Request $request)
    {
        $request->validate(['refresh_token' => 'required']);

        $hashed = hash('sha256', $request->refresh_token);
        $record = \App\Models\RefreshToken::where('token_hash', $hashed)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Refresh token tidak valid atau kadaluarsa.'], 401);
        }

        $user = User::find($record->user_id);
        
        // Rotate: revoke lama, buat baru
        $record->update(['revoked_at' => now()]);
        
        $newRaw = \Illuminate\Support\Str::random(64);
        \App\Models\RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $newRaw),
            'expires_at' => now()->addDays(7),
        ]);

        $newToken = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $newToken,
            'refresh_token' => $newRaw,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->refresh_token) {
            $hashed = hash('sha256', $request->refresh_token);
            \App\Models\RefreshToken::where('token_hash', $hashed)->update(['revoked_at' => now()]);
        }

        try {
            \Tymon\JWTAuth\Facades\JWTAuth::invalidate(\Tymon\JWTAuth\Facades\JWTAuth::getToken());
        } catch (\Exception $e) {
            // Token already invalid or not provided
        }

        if (!$request->wantsJson()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('welcome');
        }

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Send password reset link. [SEDANG #8]
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link reset dikirim ke email.'])
            : response()->json(['message' => 'Email tidak ditemukan.'], 404);
    }

    /**
     * Handle the password reset submission. [Fase 13]
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password_hash' => Hash::make($password)
                ])->save();
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password berhasil direset.'])
            : response()->json(['message' => __($status)], 400);
    }

    /**
     * Update user profile data. [NEW]
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|regex:/^\+62[0-9]{8,13}$/',
        ]);

        $user->name = $request->name;
        if ($request->phone) {
            $user->phone = $request->phone;
        }
        
        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Change user password. [NEW]
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->getAuthPassword())) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
