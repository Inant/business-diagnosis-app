@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-6">
                    <h1 class="text-2xl font-bold text-white text-center">Generate Konten Baru</h1>
                    <p class="text-pink-100 text-center mt-2">Buat ide konten berdasarkan analisa bisnis Anda</p>
                </div>

                <!-- Form -->
                <div class="p-6">
                    <form action="{{ route('front.content.generate') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Durasi Konten -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Durasi Rencana Konten (Hari)
                                </label>
                                <select name="days" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $i == 7 ? 'selected' : '' }}>
                                            {{ $i }} Hari {{ $i == 7 ? '(Recommended)' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Tujuan Pembuatan Konten -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tujuan Pembuatan Konten (Opsional)
                                </label>
                                <textarea name="tujuan_pembuatan_konten"
                                          rows="4"
                                          placeholder="Contoh: Meningkatkan brand awareness, promosi produk baru, engagement dengan audience..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent resize-none"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-300">
                                    <i class="fas fa-magic mr-2"></i>
                                    Generate Konten Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
