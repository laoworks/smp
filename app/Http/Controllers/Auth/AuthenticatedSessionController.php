<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // 🔥 SUPER ADMIN & ADMIN → ADMIN PANEL
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // 🔥 VERIFIKATOR → bisa diarahkan ke admin juga (opsional)
        if ($user->hasRole('verifikator')) {
            return redirect()->route('admin.dashboard');
        }

        // 🔥 GURU → user dashboard
        if ($user->hasRole('guru')) {
            return redirect()->route('dashboard');
        }

        // 🔥 DEFAULT USER
        return redirect()->route('dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
