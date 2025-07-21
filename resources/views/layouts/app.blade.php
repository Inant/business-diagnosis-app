<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
{{--            @include('layouts.navigation')--}}

            <nav class="bg-gray-900 text-white shadow mb-6">
                <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-8">
                        <a href="{{ url('/') }}" class="font-bold text-lg text-blue-400">Business AI App</a>

                        @auth
                            @if(auth()->user()->role === 'admin')
                                {{-- BACKOFFICE MENU --}}
                                <a href="{{ route('questions.index') }}" class="hover:text-blue-300">Manajemen Pertanyaan</a>
                                {{-- Contoh: Hasil pengisian user --}}
                                <a href="{{ route('backoffice.sessions') }}" class="hover:text-blue-300">Data Jawaban User</a>
                            @else
                                {{-- FRONTOFFICE MENU --}}
                                <a href="{{ route('front.form') }}" class="hover:text-blue-300">Isi Form Analisa</a>
                                <a href="{{ route('front.history') }}" class="hover:text-blue-300">Riwayat Analisa Saya</a>
                            @endif
                        @endauth
                    </div>
                    <div>
                        @auth
                            <span class="mr-4">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white transition">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-blue-300 mr-3">Login</a>
                            <a href="{{ route('register') }}" class="hover:text-blue-300">Register</a>
                        @endauth
                    </div>
                </div>
            </nav>
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <div class="max-w-8xl mx-auto px-4">
                @yield('content')
            </div>
        </div>
    </body>
</html>
