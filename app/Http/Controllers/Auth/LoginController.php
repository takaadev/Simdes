<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VillageOfficial;
use BackedEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'NIAP atau alamat surel wajib diisi.',
            'username.min' => 'NIAP atau alamat surel minimal 3 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $loginInput = trim($validated['username']);
        $throttleKey = Str::lower($loginInput) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return $this->failedResponse(
                $request,
                "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$seconds} detik.",
                429
            );
        }

        // Cari user berdasarkan email atau username (NIAP).
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::query()->where($loginField, $loginInput)->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return $this->failedResponse(
                $request,
                'NIAP/alamat surel atau kata sandi tidak sesuai.',
                422
            );
        }

        // Jika akun terhubung ke data aparatur (via NIAP), pastikan statusnya masih aktif.
        $official = VillageOfficial::withTrashed()
            ->where('niap', $user->username)
            ->first();

        if ($official) {
            $statusValue = $official->status instanceof BackedEnum
                ? $official->status->value
                : (string) $official->status;

            if ($official->trashed() || $statusValue !== 'active') {
                return $this->failedResponse(
                    $request,
                    'Akun aparatur ini sudah tidak aktif. Silakan hubungi Admin.',
                    403
                );
            }
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user, $request->boolean('remember_me'));
        $request->session()->regenerate();

        $redirectUrl = redirect()->intended(route('dashboard'))->getTargetUrl();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil masuk ke SIMDES.',
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect()->to($redirectUrl);
    }


    protected function failedResponse(Request $request, string $message, int $status = 422): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return back()
            ->withInput($request->only('username', 'remember_me'))
            ->withErrors(['login' => $message]);
    }
}