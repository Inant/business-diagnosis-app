@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                    <i class="fas fa-ad text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Generate Iklan Bisnis</h1>
                <p class="text-gray-600">Buat strategi iklan yang efektif berdasarkan analisa bisnis Anda</p>
            </div>

            <!-- Ads Generator Form -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form method="POST" action="{{ route('front.ads.generate') }}" id="ads-form">
                    @csrf

                    <!-- Form Header -->
                    <div class="p-8 md:p-12">
                        <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
                            <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-bullhorn text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Generator Iklan</h3>
                                <p class="text-gray-500">Pilih platform dan detail kampanye Anda</p>
                            </div>
                        </div>

                        <!-- Platform Selection -->
                        <div class="mb-8">
                            <label class="block text-gray-700 font-semibold mb-4">
                                <i class="fas fa-share-alt mr-2 text-blue-500"></i>
                                Pilih Platform Iklan
                            </label>

                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="platform-option">
                                    <input type="radio" name="platform" value="facebook_instagram" id="facebook_instagram" class="hidden" required>
                                    <label for="facebook_instagram" class="platform-card block p-6 border-2 border-gray-200 rounded-xl text-center cursor-pointer transition-all duration-300 hover:border-blue-300 hover:shadow-md">
                                        <i class="fab fa-facebook text-3xl text-blue-600 mb-3"></i>
                                        <div class="text-lg font-bold text-gray-800 mb-2">Facebook & Instagram</div>
                                        <div class="text-sm text-gray-600">Iklan visual dengan targeting detail</div>
                                    </label>
                                </div>

                                <div class="platform-option">
                                    <input type="radio" name="platform" value="tiktok" id="tiktok" class="hidden">
                                    <label for="tiktok" class="platform-card block p-6 border-2 border-gray-200 rounded-xl text-center cursor-pointer transition-all duration-300 hover:border-pink-300 hover:shadow-md">
                                        <i class="fab fa-tiktok text-3xl text-pink-600 mb-3"></i>
                                        <div class="text-lg font-bold text-gray-800 mb-2">TikTok</div>
                                        <div class="text-sm text-gray-600">Video kreatif untuk audiens muda</div>
                                    </label>
                                </div>

                                <div class="platform-option">
                                    <input type="radio" name="platform" value="google_search" id="google_search" class="hidden">
                                    <label for="google_search" class="platform-card block p-6 border-2 border-gray-200 rounded-xl text-center cursor-pointer transition-all duration-300 hover:border-green-300 hover:shadow-md">
                                        <i class="fab fa-google text-3xl text-green-600 mb-3"></i>
                                        <div class="text-lg font-bold text-gray-800 mb-2">Google Search</div>
                                        <div class="text-sm text-gray-600">Iklan berbasis keyword pencarian</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Goal -->
                        <div class="mb-8">
                            <label for="goal" class="block text-gray-700 font-semibold mb-4">
                                <i class="fas fa-bullseye mr-2 text-green-500"></i>
                                Tujuan Utama Iklan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="goal" name="goal" required
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-300 text-lg resize-none"
                                      rows="3"
                                      placeholder="Contoh: Meningkatkan penjualan paket bundling menu A dan B dengan target 100 transaksi per bulan"></textarea>
                            <p class="text-gray-500 text-sm mt-2">Jelaskan dengan spesifik apa yang ingin Anda capai dengan iklan ini</p>
                        </div>

                        <!-- Product/Service -->
                        <div class="mb-8">
                            <label for="product" class="block text-gray-700 font-semibold mb-4">
                                <i class="fas fa-box mr-2 text-purple-500"></i>
                                Produk/Layanan yang Dipromosikan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="product" name="product" required
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-lg resize-none"
                                      rows="3"
                                      placeholder="Contoh: Paket bundling menu ayam geprek + nasi + minuman dengan harga Rp 25.000"></textarea>
                            <p class="text-gray-500 text-sm mt-2">Sebutkan produk/layanan secara detail termasuk harga jika ada</p>
                        </div>

                        <!-- Special Offer -->
                        <div class="mb-8">
                            <label for="offer" class="block text-gray-700 font-semibold mb-4">
                                <i class="fas fa-gift mr-2 text-orange-500"></i>
                                Penawaran Spesial (Opsional)
                            </label>
                            <textarea id="offer" name="offer"
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-lg resize-none"
                                      rows="2"
                                      placeholder="Contoh: Diskon 20% untuk pembelian pertama, gratis ongkir, buy 1 get 1, dll"></textarea>
                            <p class="text-gray-500 text-sm mt-2">Tambahkan promo atau penawaran khusus untuk meningkatkan daya tarik</p>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-8 p-6 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-800 mb-3">
                                <i class="fas fa-eye mr-2 text-blue-500"></i>
                                Yang Akan Anda Dapatkan:
                            </h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-800">Strategi Iklan Lengkap</h5>
                                        <p class="text-gray-600 text-sm">Copy iklan, targeting, dan creative brief</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                        <i class="fas fa-users text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-800">Rekomendasi Targeting</h5>
                                        <p class="text-gray-600 text-sm">Audiens yang tepat berdasarkan analisa bisnis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-gray-50 px-8 py-6 md:px-12">
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <span class="text-sm">Iklan akan disesuaikan dengan analisa bisnis Anda</span>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                                <a href="{{ route('front.ads.history') }}"
                                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 text-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                                </a>
                                <button type="submit" id="generate-btn"
                                        class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                                    <i class="fas fa-magic mr-2"></i>Generate Iklan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .platform-option input[type="radio"]:checked + .platform-card {
            border-color: #3b82f6;
            background-color: #f0f9ff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .platform-card:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ads-form');
            const generateBtn = document.getElementById('generate-btn');

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                // Update button state
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
                generateBtn.disabled = true;
                generateBtn.classList.add('opacity-75');

                // Add loading class to form
                form.classList.add('loading');
            });
        });
    </script>
@endsection
