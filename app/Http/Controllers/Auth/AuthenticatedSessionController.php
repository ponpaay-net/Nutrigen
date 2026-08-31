<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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
        
        $redirectUrl = RouteServiceProvider::HOME;
        if ($user->role === 'puskesmas') {
            $redirectUrl = route('puskesmas.dashboard');
        } elseif ($user->role === 'kader') {
            $redirectUrl = route('kader.dashboard');
        } elseif ($user->role === 'ibu') {
            $redirectUrl = route('portal-ibu.home');
        }

        return redirect()->intended($redirectUrl)->with('success', 'Berhasil masuk ke akun Anda.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Anda telah keluar dari akun.');
    }
}
