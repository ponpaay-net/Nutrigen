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

        // KRITIS-02: Portal Ibu memakai middleware 'signed', jadi redirect
        // WAJIB memakai temporarySignedRoute + membawa orang_tua (anti-IDOR),
        // sama seperti route /dashboard. Redirect biar intended() dilarang untuk ibu
        // karena URL signed tidak tersimpan di session intended.
        if ($user->role === 'ibu') {
            $orangTua = \App\Models\OrangTua::where('user_id', $user->id)->first();

            if (!$orangTua || $orangTua->balitas()->count() === 0) {
                return redirect()->route('team')->with('info', 'Belum ada data balita yang tertaut dengan akun Ibu ini.');
            }

            $ttlDays = (int) config('portal.link_ttl_days', 7);

            if ($orangTua->balitas()->count() === 1) {
                return redirect()->to(\Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'portal-ibu.home',
                    now()->addDays($ttlDays),
                    ['balita' => $orangTua->balitas()->first()->id, 'orang_tua' => $orangTua->id]
                ))->with('success', 'Berhasil masuk ke akun Anda.');
            }

            return redirect()->to(\Illuminate\Support\Facades\URL::temporarySignedRoute(
                'portal-ibu.child-selector',
                now()->addDays($ttlDays),
                ['orang_tua' => $orangTua->id]
            ))->with('success', 'Berhasil masuk ke akun Anda.');
        }

        $redirectUrl = RouteServiceProvider::HOME;
        if ($user->role === 'super_admin') {
            $redirectUrl = route('super-admin.dashboard');
        } elseif ($user->role === 'puskesmas') {
            $redirectUrl = route('puskesmas.dashboard');
        } elseif ($user->role === 'kader') {
            $redirectUrl = route('kader.dashboard');
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
