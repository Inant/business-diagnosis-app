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
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/gurita-digital-bg.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gurita-digital-bg.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/gurita-digital-bg.png') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center pt-4 sm:pt-0 bg-gray-100">
        <div class="mb-4 w-full flex justify-center">
            <a href="/" class="block w-full max-w-[160px] sm:max-w-[100px]">
                <x-application-logo class="w-full h-auto mx-auto" />
            </a>
        </div>
        <div class="w-full max-w-xs sm:max-w-md mt-0 px-4 sm:px-6 py-6 bg-white shadow-md overflow-hidden rounded-lg">
            {{ $slot }}
        </div>
    </div>
    </body>
</html>
