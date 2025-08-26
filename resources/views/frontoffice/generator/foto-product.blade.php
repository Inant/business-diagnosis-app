@extends('layouts.app')

@section('title', 'AI Product Image Generator')

@section('content')
    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 py-8">
        <div class="max-w-6xl mx-auto px-4" x-data="{ loading:false }">
            <header class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600">
                    AI Product Image Generator
                </span>
                </h1>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Generate foto product mu dengan AI.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-800 p-6">
                    <form method="POST" action="{{ route('foto-product.generate') }}" @submit="loading=true">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium">Jenis Produk</label>
                                <input name="productName" value="{{ old('productName', data_get($inputs,'productName')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Minuman beras kencur">
                            </div>
                            <div>
                                <label class="text-sm font-medium">Merk (Opsional)</label>
                                <input name="brandName" value="{{ old('brandName', data_get($inputs,'brandName')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Jamu Mbok Ijah">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Headline (Opsional)</label>
                                <input name="headline" value="{{ old('headline', data_get($inputs,'headline')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Rahasia Segar Alami">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Font Headline</label>
                                <select name="fontFamily" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    @php
                                        $fontOptions = [
                                            'sans-serif' => 'Default',
                                            "'Roboto', sans-serif" => 'Roboto',
                                            "'Poppins', sans-serif" => 'Poppins',
                                            "'Lato', sans-serif" => 'Lato',
                                            "'Montserrat', sans-serif" => 'Montserrat',
                                            "'Oswald', sans-serif" => 'Oswald',
                                            "'Raleway', sans-serif" => 'Raleway',
                                            "'Playfair Display', serif" => 'Playfair Display',
                                            "'Merriweather', serif" => 'Merriweather',
                                            "'Lobster', cursive" => 'Lobster',
                                            "'Pacifico', cursive" => 'Pacifico',
                                            "'Bebas Neue', cursive" => 'Bebas Neue',
                                        ];
                                    @endphp
                                    @foreach($fontOptions as $val => $label)
                                        <option value="{{ $val }}" @selected(old('fontFamily', data_get($inputs,'fontFamily','sans-serif')) === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Gaya Visual</label>
                                <input name="visualStyle" value="{{ old('visualStyle', data_get($inputs,'visualStyle')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Photorealistic">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Background</label>
                                <input name="background" value="{{ old('background', data_get($inputs,'background')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Putih, shadow halus">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Lighting</label>
                                <select name="lighting" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    @foreach(['','Dramatic lighting','Soft light','Natural light','Backlight','Low-key lighting'] as $opt)
                                        <option value="{{ $opt }}" @selected(old('lighting', data_get($inputs,'lighting')) === $opt)>{{ $opt ?: 'Pilih pencahayaan...' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Komposisi / Angle</label>
                                <select name="composition" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    @foreach(['','Packshot','Close-up','Flatlay','High angle','Low angle'] as $opt)
                                        <option value="{{ $opt }}" @selected(old('composition', data_get($inputs,'composition')) === $opt)>{{ $opt ?: 'Pilih komposisi...' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium">Props</label>
                                <input name="props" value="{{ old('props', data_get($inputs,'props')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Jahe, kunyit">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Mood</label>
                                <select name="mood" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    @php
                                        $moods = ["","Natural & sehat","Mewah & elegan","Cozy & hangat","Enerjik & ceria","Clean & fresh","Minimalis & modern","Rustic & tradisional","Misterius & dramatis","Playful & menyenangkan","Futuristik & canggih"];
                                    @endphp
                                    @foreach($moods as $m)
                                        <option value="{{ $m }}" @selected(old('mood', data_get($inputs,'mood')) === $m)>{{ $m ?: 'Pilih suasana...' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Branding / Label (Opsional)</label>
                                <input name="branding" value="{{ old('branding', data_get($inputs,'branding')) }}"
                                       class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" placeholder="Contoh: Label terlihat jelas">
                            </div>

                            <div>
                                <label class="text-sm font-medium">Orientasi & Rasio</label>
                                <select name="orientation" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="Square 1:1" @selected(old('orientation', data_get($inputs,'orientation')) === "Square 1:1")>
                                        Square 1:1
                                    </option>
{{--                                    @foreach(['','Square 1:1','Vertical 9:16','Vertical 4:5','Horizontal 16:9','Horizontal 1.91:1'] as $o)--}}
{{--                                        <option value="{{ $o }}" @selected(old('orientation', data_get($inputs,'orientation')) === $o)>{{ $o ?: 'Pilih orientasi...' }}</option>--}}
{{--                                    @endforeach--}}
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Detail Tambahan</label>
                                <textarea name="additionalDetails" rows="3"
                                          class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                          placeholder="Contoh: Fokus pada tetesan air di botol">{{ old('additionalDetails', data_get($inputs,'additionalDetails')) }}</textarea>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                                <ul class="list-disc ml-5">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($error))
                            <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                                {{ $error }}
                            </div>
                        @endif

                        <button type="submit"
                                class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M2 12h14.5l-4.5-4.5L13.5 6 21 13.5 13.5 21 12 19.5 16.5 15H2z"/></svg>
                            Generate Foto Produk
                        </button>

                        <!-- Spinner saat submit -->
                        <div x-show="loading" class="flex justify-center mt-4" x-cloak>
                            <svg class="animate-spin h-6 w-6 text-blue-500" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity=".25"/>
                                <path d="M22 12a10 10 0 0 1-10 10" fill="currentColor" opacity=".75"></path>
                            </svg>
                            <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Generating...</span>
                        </div>
                    </form>
                </div>

                <!-- Output -->
                <div class="space-y-6">
{{--                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-800 p-6">--}}
{{--                        <h2 class="text-lg font-semibold mb-2">Prompt yang Dihasilkan</h2>--}}
{{--                        @if($generatedPrompt)--}}
{{--                            <pre class="p-4 rounded-lg bg-gray-100 dark:bg-gray-900 overflow-x-auto text-sm">{{ $generatedPrompt }}</pre>--}}
{{--                        @else--}}
{{--                            <p class="text-gray-500 dark:text-gray-400 text-sm">Prompt akan muncul di sini setelah generate.</p>--}}
{{--                        @endif--}}
{{--                    </div>--}}

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-800 p-6">
                        <h2 class="text-lg font-semibold mb-2">Hasil Gambar</h2>
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="Hasil Gambar" class="rounded-xl shadow-lg max-w-full" />
                            <div class="mt-4">
                                <a href="{{ $imageUrl }}" download class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    Download
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada gambar. Generate terlebih dahulu.</p>
                        @endif
                    </div>
                </div>
            </div>

{{--            <footer class="text-center mt-10 text-xs text-gray-500 dark:text-gray-400">--}}
{{--                Pastikan kamu sudah meng-upload foto referensi produk di chat sebelum generate.--}}
{{--            </footer>--}}
        </div>
    </div>
@endsection
