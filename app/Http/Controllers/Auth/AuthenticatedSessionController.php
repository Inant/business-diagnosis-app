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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Debugging: Cek User & Password
        $user = \App\Models\User::where('phone_number', $request->phone_number)->first();
        if(!$user) {
            return back()->withErrors(['phone_number' => 'Nomor HP tidak terdaftar'])->withInput();
        }
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['phone_number' => 'Password salah'])->withInput();
        }

        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['phone_number' => 'Login gagal'])->withInput($request->only('phone_number'));
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
