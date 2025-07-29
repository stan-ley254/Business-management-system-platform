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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Super Admin check
        if ($user->is_superadmin) {
            return redirect('/homeSuperAdmin');
        }

        $user->load(['role', 'business']);

        $role = $user->role?->name;
        $businessType = $user->business?->type; // expects 'pos' or 'service'

        // 🔥 Redirect based on role + business type
        if ($role === 'admin') {
            if ($businessType === 'pos') {
                return redirect('/homeAdmin');
            } elseif ($businessType === 'service') {
                return redirect('/serviceAdmin');
            }
        } elseif ($role === 'user') {
            if ($businessType === 'pos') {
                return redirect('/homeUser');
            } elseif ($businessType === 'service') {
                return redirect('/serviceUser');
            }
        }

        // ❌ Unauthorized fallback
        Auth::logout();
        return redirect('/login')->withErrors([
            'email' => 'Unauthorized role access.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
