<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController
{
    public function createMember(): View
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('member.dashboard');
        }

        return view('auth.member-login');
    }

    public function storeMember(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_admin'] = false;

        if (! Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'name' => 'The member login details are incorrect. Please try again.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('member.dashboard'));
    }

    public function createBackend(): View
    {
        if (Auth::guard('backend')->check()) {
            return redirect()->route('backend.dashboard');
        }

        return view('auth.backend-login');
    }

    public function storeBackend(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['is_admin'] = true;

        if (! Auth::guard('backend')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The backend login details are incorrect. Please try again.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('backend.dashboard'));
    }

    public function destroyMember(Request $request): RedirectResponse
    {
        Auth::guard('member')->logout();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function destroyBackend(Request $request): RedirectResponse
    {
        Auth::guard('backend')->logout();

        $request->session()->regenerateToken();

        return redirect()->route('backend.login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('member')->logout();
        Auth::guard('backend')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
