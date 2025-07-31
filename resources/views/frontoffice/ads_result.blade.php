@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Header Section with Success Animation -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-4 animate-bounce">
                    <i class="fas fa-check-circle text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Iklan Berhasil Dibuat! 🎉</h1>
                <p class="text-gray-600">AI telah menganalisis bisnis Anda dan membuat iklan yang optimal</p>
            </div>

            <!-- Main Result Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

                <!-- Header Card with Campaign Info -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold mb-2">Kampanye Iklan Anda</h2>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-white bg-opacity-20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-bullhorn mr-1"></i>
                                    @if($adsResult->platform == 'facebook_instagram')
                                        📱 Facebook/Instagram
                                    @elseif($adsResult->platform == 'tiktok')
                                        🎵 TikTok
                                    @else
                                        🔍 Google Search
                                    @endif
                                </span>
                                <span class="bg-white bg-opacity-20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-target mr-1"></i>
                                    {{ ucfirst($adsResult->goal) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm opacity-90">Dibuat pada</div>
                            <div class="font-semibold">{{ $adsResult->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Campaign Details -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Detail Kampanye
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Product/Service -->
                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                            <div class="flex items-start space-x-3">
                                <div class="bg-purple-500 rounded-lg p-2 flex-shrink-0">
                                    <i class="fas fa-box text-white text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-medium text-purple-800 text-sm mb-1">Produk/Layanan</h4>
                                    <p class="text-purple-700 text-sm break-words">{{ $adsResult->product }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Special Offer -->
                        @if($adsResult->offer)
                            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-red-500 rounded-lg p-2 flex-shrink-0">
                                        <i class="fas fa-gift text-white text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-red-800 text-sm mb-1">Penawaran Spesial</h4>
                                        <p class="text-red-700 text-sm break-words">{{ $adsResult->offer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- AI Response Section -->
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-wand-magic-sparkles text-indigo-500 mr-2"></i>
                            Hasil AI Campaign
                        </h3>
                        <button onclick="copyToClipboard()" class="text-gray-500 hover:text-indigo-600 transition-colors duration-200 p-2 rounded-lg hover:bg-indigo-50" title="Copy ke clipboard">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                    <!-- AI Response Content using formatAnalysisContent helper -->
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl p-6 md:p-8 border border-gray-200">
                        <div class="prose prose-sm md:prose-base max-w-none">
                            <div id="aiResponse" class="text-gray-800 leading-relaxed">
                                {!! formatAnalysisContent($adsResult->ai_response) !!}
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-between items-center">
                <a href="{{ route('front.history') }}"
                   class="inline-flex items-center px-6 py-3 bg-white text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200 border border-gray-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Riwayat
                </a>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="shareResult()"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-share-alt mr-2"></i>
                        Bagikan
                    </button>

                    <button onclick="window.print()"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-download mr-2"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast (Hidden by default) -->
    <div id="successToast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Berhasil disalin ke clipboard!</span>
        </div>
    </div>

    <script>
        // Copy to clipboard function - get plain text from formatted content
        function copyToClipboard() {
            const aiResponseElement = document.getElementById('aiResponse');
            const aiResponse = aiResponseElement.innerText || aiResponseElement.textContent;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(aiResponse).then(() => {
                    showToast();
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    fallbackCopy(aiResponse);
                });
            } else {
                fallbackCopy(aiResponse);
            }
        }

        // Fallback copy method
        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                showToast();
            } catch (err) {
                console.error('Fallback copy failed: ', err);
            }

            document.body.removeChild(textArea);
        }

        // Show success toast
        function showToast() {
            const toast = document.getElementById('successToast');
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');

            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
            }, 3000);
        }

        // Share function - get plain text for sharing
        function shareResult() {
            const aiResponseElement = document.getElementById('aiResponse');
            const aiResponse = aiResponseElement.innerText || aiResponseElement.textContent;
            const shareText = `Lihat iklan bisnis yang baru saja saya buat:\n\n${aiResponse.substring(0, 200)}...`;

            if (navigator.share && /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                navigator.share({
                    title: 'Hasil Generate Iklan Bisnis',
                    text: shareText,
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // Fallback: copy link to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast();
                }).catch(err => {
                    console.error('Failed to copy link: ', err);
                });
            }
        }

        // Print styles
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                body * { visibility: hidden; }
                .max-w-4xl, .max-w-4xl * { visibility: visible; }
                .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; }
                .bg-gradient-to-br { background: white !important; }
                .shadow-2xl { box-shadow: none !important; }
                button { display: none !important; }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
