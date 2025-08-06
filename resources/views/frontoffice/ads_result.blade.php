@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-ad text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Strategi Iklan Anda</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Kampanye iklan yang telah disesuaikan dengan bisnis Anda</p>
            </div>

            <!-- Campaign Info Card -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-{{ $adsPlan->platform == 'facebook_instagram' ? 'facebook' : ($adsPlan->platform == 'tiktok' ? 'tiktok' : 'google') }} text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-semibold">{{ $adsPlan->platform_label }}</h3>
                                    <p class="text-xs sm:text-sm text-blue-100">{{ $adsPlan->created_at->format('d M Y - H:i') }} WIB</p>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-lg p-3 mb-3">
                                <p class="text-xs sm:text-sm font-medium">Tujuan: {{ $adsPlan->goal }}</p>
                            </div>
                            <div class="bg-white bg-opacity-15 rounded-lg p-3">
                                <p class="text-xs sm:text-sm">Produk: {{ $adsPlan->product }}</p>
                                @if($adsPlan->offer)
                                    <p class="text-xs sm:text-sm mt-1">Penawaran: {{ $adsPlan->offer }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-row lg:flex-col space-x-3 lg:space-x-0 lg:space-y-2 w-full lg:w-auto">
                            <div class="text-center bg-white bg-opacity-20 rounded-lg px-3 py-2 flex-1 lg:flex-none">
                                <div class="text-sm sm:text-base font-bold">{{ $adsPlan->formatted_cost }}</div>
                                <div class="text-xs text-blue-100">Biaya</div>
                            </div>
                            <div class="text-center bg-white bg-opacity-20 rounded-lg px-3 py-2 flex-1 lg:flex-none">
                                <div class="text-sm sm:text-base font-bold">{{ $adsPlan->response_time_ms }}ms</div>
                                <div class="text-xs text-blue-100">Response</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parsed AI Response -->
            <div id="ads-content">
                {!! $parsedResponse !!}
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Strategi Iklan Siap Digunakan!</h3>
                        <p class="text-gray-600 text-sm">Implementasikan kampanye iklan Anda sekarang</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                        <a href="{{ route('front.ads.history') }}"
                           class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 text-center text-xs sm:text-sm">
                            <i class="fas fa-list mr-2"></i>Daftar Iklan
                        </a>

                        <a href="{{ route('front.ads.create') }}"
                           class="px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 text-center text-xs sm:text-sm">
                            <i class="fas fa-plus mr-2"></i>Generate Lagi
                        </a>

                        <button onclick="copyAdsContent()" id="copy-btn"
                                class="px-4 sm:px-6 py-2 sm:py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors duration-300 text-xs sm:text-sm">
                            <i class="fas fa-copy mr-2"></i>Copy Konten
                        </button>

                        <button onclick="exportToPDF()"
                                class="px-4 sm:px-6 py-2 sm:py-3 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors duration-300 text-xs sm:text-sm">
                            <i class="fas fa-download mr-2"></i>Export PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyAdsContent() {
            // Get clean text content without HTML
            const content = `{{ strip_tags($adsPlan->ai_response) }}`;

            navigator.clipboard.writeText(content).then(() => {
                const btn = document.getElementById('copy-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersalin!';
                btn.classList.remove('bg-green-500', 'hover:bg-green-600');
                btn.classList.add('bg-green-600');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('bg-green-600');
                    btn.classList.add('bg-green-500', 'hover:bg-green-600');
                }, 2000);
            }).catch(() => {
                alert('Gagal menyalin konten');
            });
        }

        function exportToPDF() {
            window.print();
        }

        // Add scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.ads-section').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(el);
            });
        });
    </script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-gradient-to-br { background: white !important; }
            .shadow-lg, .shadow-xl { box-shadow: none !important; }
        }
    </style>
@endsection
