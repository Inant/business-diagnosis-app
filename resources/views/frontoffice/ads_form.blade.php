@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                    <i class="fas fa-magic text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Generate Iklan Bisnis</h1>
                <p class="text-gray-600">Buat iklan yang menarik untuk berbagai platform dengan mudah</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 border border-gray-100">
                <form action="{{ route('front.ads.generate', $session_id) }}" method="POST" class="space-y-6" id="adForm">
                    @csrf

                    <!-- Platform Selection -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-bullhorn text-blue-500 mr-2"></i>
                            Platform Iklan
                        </label>
                        <select name="platform" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 hover:bg-white">
                            <option value="">-- Pilih Platform --</option>
                            <option value="facebook_instagram">📱 Facebook/Instagram Ads</option>
                            <option value="tiktok">🎵 Tiktok Ads</option>
                            <option value="google_search">🔍 Google Search Ads</option>
                        </select>
                    </div>

                    <!-- Goal Selection -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-target text-green-500 mr-2"></i>
                            Tujuan Utama Iklan
                        </label>
                        <select name="goal" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 hover:bg-white">
                            <option value="">-- Pilih Tujuan --</option>
                            <option value="website">🌐 Meningkatkan kunjungan ke Website/Toko Online</option>
                            <option value="whatsapp">💬 Mendapatkan lebih banyak chat WhatsApp/DM</option>
                            <option value="penjualan">💰 Meningkatkan penjualan produk spesifik</option>
                            <option value="brand">✨ Memperkenalkan merek kepada audiens baru</option>
                        </select>
                    </div>

                    <!-- Product Input -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-box text-purple-500 mr-2"></i>
                            Produk/Layanan yang Dipromosikan
                        </label>
                        <input type="text"
                               name="product"
                               required
                               placeholder="Contoh: Jasa Pemasangan Kusen Tipe A"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 hover:bg-white placeholder-gray-400">
                    </div>

                    <!-- Special Offer Input -->
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="fas fa-gift text-red-500 mr-2"></i>
                            Penawaran Spesial
                            <span class="text-xs text-gray-500 ml-1">(Opsional)</span>
                        </label>
                        <input type="text"
                               name="offer"
                               placeholder="Contoh: Diskon 20% hingga akhir bulan"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50 hover:bg-white placeholder-gray-400">
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                id="submitBtn"
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-200 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">

                            <!-- Loading Spinner (Hidden by default) -->
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loadingSpinner" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>

                            <!-- Default Content -->
                            <span id="defaultContent" class="flex items-center">
                                <i class="fas fa-wand-magic-sparkles mr-2"></i>
                                Generate Iklan Sekarang
                                <i class="fas fa-arrow-right ml-2"></i>
                            </span>

                            <!-- Loading Content (Hidden by default) -->
                            <span id="loadingContent" class="hidden">
                                Sedang Membuat Iklan...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Footer Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-1">Tips untuk hasil terbaik:</p>
                            <ul class="text-xs space-y-1 text-blue-600">
                                <li>• Deskripsikan produk/layanan secara spesifik</li>
                                <li>• Cantumkan penawaran menarik jika ada</li>
                                <li>• Pilih platform sesuai target audiens Anda</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay (Hidden by default) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-500 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Sedang Membuat Iklan</h3>
                <p class="text-gray-600 text-sm">AI sedang menganalisis data Anda...</p>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full animate-pulse" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('adForm').addEventListener('submit', function(e) {
            // Get elements
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const defaultContent = document.getElementById('defaultContent');
            const loadingContent = document.getElementById('loadingContent');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Disable button
            submitBtn.disabled = true;

            // Show loading spinner and change text
            loadingSpinner.classList.remove('hidden');
            defaultContent.classList.add('hidden');
            loadingContent.classList.remove('hidden');

            // Show overlay
            loadingOverlay.classList.remove('hidden');

            // Optional: Add body scroll lock
            document.body.style.overflow = 'hidden';

            // If form validation fails, reset button state
            setTimeout(() => {
                if (!this.checkValidity()) {
                    resetButton();
                }
            }, 100);
        });

        function resetButton() {
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const defaultContent = document.getElementById('defaultContent');
            const loadingContent = document.getElementById('loadingContent');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Re-enable button
            submitBtn.disabled = false;

            // Hide loading, show default
            loadingSpinner.classList.add('hidden');
            defaultContent.classList.remove('hidden');
            loadingContent.classList.add('hidden');

            // Hide overlay
            loadingOverlay.classList.add('hidden');

            // Restore body scroll
            document.body.style.overflow = '';
        }

        // Reset on page load (in case of back button)
        window.addEventListener('load', function() {
            resetButton();
        });

        // Handle form validation errors
        const form = document.getElementById('adForm');
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('invalid', function() {
                resetButton();
            });
        });
    </script>
@endsection
