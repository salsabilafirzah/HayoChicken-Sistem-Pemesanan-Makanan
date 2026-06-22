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

        // SDD Lapis 2: Batas gagal login (5x dalam 5 Menit per akun/IP)
        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $message = "Akun dikunci sementara karena terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.";
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 429);
            }
            return back()->with('error', $message);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 300); // Kunci 5 Menit (300 detik)
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Kredensial tidak valid.'], 401);
            }
            return back()->with('error', 'Kredensial tidak valid.');
        }

        \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

        // Jika request dari web browser (bukan API), gunakan session login standar Laravel
        if (!$request->wantsJson()) {
            Auth::guard('web')->login($user, true);
            if ($request->hasSession()) {
                $request->session()->regenerate();
                $request->session()->put('logged_in_at', now()->toDateTimeString());
                $request->session()->save();
            }

            if ($user->role === 'SELLER') {
                return redirect()->route('seller.dashboard');
            }
            return redirect()->route('home');
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
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'email' => $user->email,
                'phone' => $user->phone
            ]
        ]);
    }

    public function register(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Register request received', ['json' => $request->wantsJson(), 'data' => $request->except(['password', 'password_confirmation'])]);
        
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => ['required', 'string', 'regex:/^[0-9+]{10,15}$/'],
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

        if (!$request->wantsJson()) {
            Auth::guard('web')->login($user);
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang.');
        }

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

    public function me(Request $request)
    {
        // Route ini ditujukan untuk memberikan data user terbaru via API
        return response()->json([
            'success' => true,
            'user' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'role' => Auth::user()->role,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone,
            ]
        ]);
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
        try {
            // JURUS PAMUNGKAS: Ambil user langsung dari Token JWT tanpa nunggu middleware
            $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Sesi berakhir atau token tidak valid.'], 401);
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 401);
        }

        try {
            $request->validate([
                'name'  => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
            ]);

            $user->name = $request->name;
            if ($request->phone) {
                $user->phone = $request->phone;
            }
            
            $user->save();

            return response()->json([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user password data via API. [NEW]
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Sesi berakhir atau token tidak valid.'], 401);
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 401);
        }

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password_hash)) {
            return response()->json(['success' => false, 'message' => 'Password saat ini salah.'], 400);
        }

        try {
            $user->password_hash = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui password.'], 500);
        }
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
