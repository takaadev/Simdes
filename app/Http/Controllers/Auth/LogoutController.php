<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $message = 'Anda berhasil keluar dari SIMDES.';
        if ($request->expectsJson() || $request->ajax()) {
            $request->session()->flash('status', $message);
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('login'),
            ]);
        }
        return redirect()->route('login')->with('status', $message);
    }
}
