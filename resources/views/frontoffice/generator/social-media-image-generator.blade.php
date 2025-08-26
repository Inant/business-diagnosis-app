@extends('layouts.app')

@section('title', 'Generator Prompt Media Sosial')

@section('content')
    {{-- ====== Asset sederhana (tanpa push) ====== --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lobster&family=Lora&family=Oswald&family=Playfair+Display&family=Roboto&family=Poppins&family=Montserrat&family=Merriweather&family=Pacifico&family=Bebas+Neue&display=swap"
        rel="stylesheet">

    {{-- Favicon (opsional) --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/gurita-digital-bg.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/gurita-digital-bg.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/gurita-digital-bg.png') }}">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    <div class="w-full max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-2xl rounded-2xl p-6 md:p-10 space-y-6">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
                Generator Media Sosial Post
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Isi form di bawah untuk generate gambar.
            </p>
        </div>

        {{-- Notifikasi --}}
        @if(session('warning'))
            <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-3 text-yellow-800 dark:border-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200">
                {{ session('warning') }}
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form id="generatorForm" method="POST" action="{{ route('social-media-image-generator.generate') }}" class="space-y-6">
            @csrf

            {{-- 1. Isi Campaign / Brief --}}
            <div>
                <label for="isiCampaign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    1. Isi Campaign / Brief
                </label>
                <textarea id="isiCampaign" name="isiCampaign" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                          placeholder="Contoh: Diskon spesial 50% untuk produk kopi terbaru 'Java Blend' selama bulan Juli!">{{ old('isiCampaign') }}</textarea>
                @error('isiCampaign')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Jenis Font Utama --}}
            <div>
                <label for="jenisFont" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    2. Jenis Font Utama
                </label>
                <select id="jenisFont" name="jenisFont"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                    @php $oldJenisFont = old('jenisFont'); @endphp
                    <option value="Sans-serif Modern (seperti Roboto)" style="font-family: 'Roboto', sans-serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Sans-serif Modern (seperti Roboto)' ? 'selected' : '' }}>
                        Roboto (Modern & Jelas)
                    </option>
                    <option value="Sans-serif Geometris (seperti Poppins)" style="font-family: 'Poppins', sans-serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Sans-serif Geometris (seperti Poppins)' ? 'selected' : '' }}>
                        Poppins (Ramah & Profesional)
                    </option>
                    <option value="Sans-serif Versatil (seperti Montserrat)" style="font-family: 'Montserrat', sans-serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Sans-serif Versatil (seperti Montserrat)' ? 'selected' : '' }}>
                        Montserrat (Stylish & Urban)
                    </option>
                    <option value="Serif Elegan (seperti Lora)" style="font-family: 'Lora', serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Serif Elegan (seperti Lora)' ? 'selected' : '' }}>
                        Lora (Elegan & Klasik)
                    </option>
                    <option value="Serif Tradisional (seperti Merriweather)" style="font-family: 'Merriweather', serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Serif Tradisional (seperti Merriweather)' ? 'selected' : '' }}>
                        Merriweather (Mudah Dibaca & Formal)
                    </option>
                    <option value="Serif Display (seperti Playfair Display)" style="font-family: 'Playfair Display', serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Serif Display (seperti Playfair Display)' ? 'selected' : '' }}>
                        Playfair Display (Mewah & Artistik)
                    </option>
                    <option value="Script Playful (seperti Lobster)" style="font-family: 'Lobster', cursive; font-size:1.2rem"
                        {{ $oldJenisFont === 'Script Playful (seperti Lobster)' ? 'selected' : '' }}>
                        Lobster (Playful & Kasual)
                    </option>
                    <option value="Script Handwriting (seperti Pacifico)" style="font-family: 'Pacifico', cursive; font-size:1.2rem"
                        {{ $oldJenisFont === 'Script Handwriting (seperti Pacifico)' ? 'selected' : '' }}>
                        Pacifico (Santai & Personal)
                    </option>
                    <option value="Sans-serif Tebal (seperti Oswald)" style="font-family: 'Oswald', sans-serif; font-size:1.1rem"
                        {{ $oldJenisFont === 'Sans-serif Tebal (seperti Oswald)' ? 'selected' : '' }}>
                        Oswald (Tebal & Tegas)
                    </option>
                    <option value="Display Headline (seperti Bebas Neue)" style="font-family: 'Bebas Neue', cursive; font-size:1.2rem"
                        {{ $oldJenisFont === 'Display Headline (seperti Bebas Neue)' ? 'selected' : '' }}>
                        Bebas Neue (Judul & Impactful)
                    </option>
                </select>
                @error('jenisFont')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 3. Gaya Desain Campaign --}}
                <div>
                    <label for="desainCampaign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        3. Gaya Desain Campaign
                    </label>
                    @php $oldDesain = old('desainCampaign'); @endphp
                    <select id="desainCampaign" name="desainCampaign"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                        <option {{ $oldDesain === 'Minimalis & Modern' ? 'selected' : '' }}>Minimalis & Modern</option>
                        <option {{ $oldDesain === 'Fun & Colorful' ? 'selected' : '' }}>Fun & Colorful</option>
                        <option {{ $oldDesain === 'Elegan & Mewah' ? 'selected' : '' }}>Elegan & Mewah</option>
                        <option {{ $oldDesain === 'Retro & Vintage' ? 'selected' : '' }}>Retro & Vintage</option>
                        <option {{ $oldDesain === 'Futuristik & Teknologi' ? 'selected' : '' }}>Futuristik & Teknologi</option>
                        <option {{ $oldDesain === 'Natural & Organik' ? 'selected' : '' }}>Natural & Organik</option>
                    </select>
                    @error('desainCampaign')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Rasio Ukuran Gambar --}}
                <div>
                    <label for="rasioGambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        4. Rasio Ukuran Gambar
                    </label>
                    @php $oldRasio = old('rasioGambar'); @endphp
                    <select id="rasioGambar" name="rasioGambar"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                        <option value="1:1 (persegi)" {{ $oldRasio === '1:1 (persegi)' ? 'selected' : '' }}>
                            1:1 (Instagram Post, Facebook Post)
                        </option>
                        {{-- Catatan: rasio lain bisa ditambah sesuai dukungan model gambar --}}
                    </select>
                    @error('rasioGambar')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 5. CTA --}}
            <div>
                <label for="cta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    5. Call to Action (CTA)
                </label>
                <input type="text" id="cta" name="cta"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                       placeholder="Contoh: Beli Sekarang, Swipe Up, Info Lebih Lanjut"
                       value="{{ old('cta') }}">
                @error('cta')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- 6. Jenis Font untuk CTA --}}
            <div>
                <label for="jenisFontCta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    6. Jenis Font untuk CTA
                </label>
                @php $oldJenisFontCta = old('jenisFontCta'); @endphp
                <select id="jenisFontCta" name="jenisFontCta"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                    <option value="Sans-serif Modern (seperti Roboto)" style="font-family:'Roboto',sans-serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Sans-serif Modern (seperti Roboto)' ? 'selected' : '' }}>
                        Roboto (Modern & Jelas)
                    </option>
                    <option value="Sans-serif Geometris (seperti Poppins)" style="font-family:'Poppins',sans-serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Sans-serif Geometris (seperti Poppins)' ? 'selected' : '' }}>
                        Poppins (Ramah & Profesional)
                    </option>
                    <option value="Sans-serif Versatil (seperti Montserrat)" style="font-family:'Montserrat',sans-serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Sans-serif Versatil (seperti Montserrat)' ? 'selected' : '' }}>
                        Montserrat (Stylish & Urban)
                    </option>
                    <option value="Serif Elegan (seperti Lora)" style="font-family:'Lora',serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Serif Elegan (seperti Lora)' ? 'selected' : '' }}>
                        Lora (Elegan & Klasik)
                    </option>
                    <option value="Serif Tradisional (seperti Merriweather)" style="font-family:'Merriweather',serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Serif Tradisional (seperti Merriweather)' ? 'selected' : '' }}>
                        Merriweather (Mudah Dibaca & Formal)
                    </option>
                    <option value="Serif Display (seperti Playfair Display)" style="font-family:'Playfair Display',serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Serif Display (seperti Playfair Display)' ? 'selected' : '' }}>
                        Playfair Display (Mewah & Artistik)
                    </option>
                    <option value="Script Playful (seperti Lobster)" style="font-family:'Lobster',cursive;font-size:1.2rem"
                        {{ $oldJenisFontCta === 'Script Playful (seperti Lobster)' ? 'selected' : '' }}>
                        Lobster (Playful & Kasual)
                    </option>
                    <option value="Script Handwriting (seperti Pacifico)" style="font-family:'Pacifico',cursive;font-size:1.2rem"
                        {{ $oldJenisFontCta === 'Script Handwriting (seperti Pacifico)' ? 'selected' : '' }}>
                        Pacifico (Santai & Personal)
                    </option>
                    <option value="Sans-serif Tebal (seperti Oswald)" style="font-family:'Oswald',sans-serif;font-size:1.1rem"
                        {{ $oldJenisFontCta === 'Sans-serif Tebal (seperti Oswald)' ? 'selected' : '' }}>
                        Oswald (Tebal & Tegas)
                    </option>
                    <option value="Display Headline (seperti Bebas Neue)" style="font-family:'Bebas Neue',cursive;font-size:1.2rem"
                        {{ $oldJenisFontCta === 'Display Headline (seperti Bebas Neue)' ? 'selected' : '' }}>
                        Bebas Neue (Judul & Impactful)
                    </option>
                </select>
                @error('jenisFontCta')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- 7. Preferensi Warna Background --}}
            <div>
                <label for="warnaBackground" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    7. Preferensi Warna Background
                </label>
                <input type="text" id="warnaBackground" name="warnaBackground"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                       placeholder="Contoh: Biru laut, gradasi oranye ke pink, pastel lembut"
                       value="{{ old('warnaBackground') }}">
                @error('warnaBackground')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- 8. Detail Tambahan --}}
            <div>
                <label for="detilTambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    8. Detail Tambahan
                </label>
                <textarea id="detilTambahan" name="detilTambahan" rows="2"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                          placeholder="Contoh: Tambahkan elemen daun tropis, jangan ada gambar orang.">{{ old('detilTambahan') }}</textarea>
                @error('detilTambahan')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2 text-center">
                <button id="submitBtn" type="submit"
                        class="w-full md:w-auto inline-flex justify-center items-center px-8 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-transform hover:scale-105">
                    <svg class="hidden mr-2 h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span class="btn-text">Generate</span>
                </button>
            </div>
        </form>

        {{-- ================= HASIL ================= --}}
        @if(!empty($generatedPrompt))
            <div class="pt-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">✅ Gambar Berhasil Digenerate!</h2>

                @if(!empty($imageUrl))
                    <div class="mt-6">
                        <img src="{{ $imageUrl }}" alt="Hasil Gambar"
                             class="w-full rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <a href="{{ route('social-media-image-generator.download', ['u' => urlencode($imageUrl)]) }}"
                           class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-gray-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3a1 1 0 011 1v9.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L11 13.586V4a1 1 0 011-1z"/>
                                <path d="M5 20a1 1 0 001 1h12a1 1 0 001-1v-3a1 1 0 112 0v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3a1 1 0 112 0v3z"/>
                            </svg>
                            Unduh Gambar
                        </a>

                        <a href="{{ $imageUrl }}" target="_blank" rel="noopener"
                           class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                            Buka di Tab Baru
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ============ Overlay Loading ============ --}}
    <div id="loadingOverlay"
         class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-sm flex items-center justify-center">
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow-xl px-6 py-5 flex items-center gap-3">
            <svg class="h-7 w-7 animate-spin text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" aria-hidden="true" role="img">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <div class="text-sm">
                <p class="font-semibold text-gray-900 dark:text-gray-100">Memproses gambar…</p>
                <p class="text-gray-600 dark:text-gray-400">Mohon tunggu, ini bisa memakan waktu hingga beberapa menit.</p>
            </div>
        </div>
    </div>

    {{-- ============ Script inline (tanpa push) ============ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('generatorForm');
            const btn  = document.getElementById('submitBtn');
            const overlay = document.getElementById('loadingOverlay');
            if (!form || !btn || !overlay) return;

            form.addEventListener('submit', function () {
                overlay.classList.remove('hidden');
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                const spinner = btn.querySelector('svg');
                const textEl  = btn.querySelector('.btn-text');
                if (spinner) spinner.classList.remove('hidden');
                if (textEl)   textEl.textContent = 'Memproses…';
            });
        });
    </script>
@endsection
