<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                'regex:/@/'
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^08[0-9]{8,11}$/',
                'min:10',
                'max:13',
                'unique:' . User::class
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan' => ['required', 'in:standard,pro'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'email.regex' => 'Alamat email harus mengandung karakter @.',
            'phone_number.required' => 'Nomor handphone wajib diisi.',
            'phone_number.regex' => 'Nomor handphone harus dimulai dengan 08 dan terdiri dari 10 sampai 13 digit.',
            'phone_number.min' => 'Nomor handphone minimal 10 digit.',
            'phone_number.max' => 'Nomor handphone maksimal 13 digit.',
            'phone_number.unique' => 'Nomor handphone sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'plan.required' => 'Pilih plan yang diinginkan.',
            'plan.in' => 'Pilihan plan tidak valid.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'plan' => $request->plan,
            'role' => 'user'
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('front.form');
    }
}
