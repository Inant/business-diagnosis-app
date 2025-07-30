@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100 py-4 sm:py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full mb-4">
                    <i class="fas fa-video text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Generate Shooting Script</h1>
                <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto px-4">Buat script shooting yang detail dan terstruktur untuk konten video Anda</p>
            </div>

            <!-- Content Overview -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 mb-6 sm:mb-8">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Overview Konten
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-gradient-to-r
                            @if($contentIdea->pilar_konten === 'Edukasi') from-blue-500 to-indigo-600
                            @elseif($contentIdea->pilar_konten === 'Inspirasi') from-pink-500 to-rose-600
                            @else from-green-500 to-emerald-600 @endif
                            rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">{{ $contentIdea->hari_ke }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $contentIdea->judul_konten }}</h3>
                                <p class="text-xs sm:text-sm text-gray-600">{{ $contentIdea->pilar_konten }} - Hari ke-{{ $contentIdea->hari_ke }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm text-gray-700 font-medium">Hook:</p>
                            <p class="text-xs sm:text-sm text-gray-600 italic">"{{ $contentIdea->hook }}"</p>
                        </div>
                    </div>
                    <div>
                        <div class="bg-gradient-to-r
                        @if($contentIdea->pilar_konten === 'Edukasi') from-blue-50 to-indigo-50 border-blue-200
                        @elseif($contentIdea->pilar_konten === 'Inspirasi') from-pink-50 to-rose-50 border-pink-200
                        @else from-green-50 to-emerald-50 border-green-200 @endif
                        border-l-4 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2">Call to Action:</p>
                            <p class="text-xs sm:text-sm text-gray-600">{{ $contentIdea->call_to_action }}</p>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
                            @if($contentIdea->pilar_konten === 'Edukasi') bg-blue-100 text-blue-800
                            @elseif($contentIdea->pilar_konten === 'Inspirasi') bg-pink-100 text-pink-800
                            @else bg-green-100 text-green-800 @endif">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $contentIdea->rekomendasi_format }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Script Generation -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                        <i class="fas fa-cog mr-2 text-orange-500"></i>
                        Konfigurasi Shooting Script
                    </h2>
                    <p class="text-gray-600 text-xs sm:text-sm mt-1">Sesuaikan parameter script sesuai kebutuhan konten Anda</p>
                </div>

                <form action="{{ route('front.shooting.generate', $contentIdea->id) }}" method="POST" class="p-4 sm:p-6">
                    @csrf

                    <!-- Gaya Pembawaan -->
                    <div class="mb-6 sm:mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-3 sm:mb-4">
                            <i class="fas fa-theater-masks mr-2 text-purple-500"></i>
                            Gaya Pembawaan
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <label class="relative">
                                <input type="radio" name="gaya_pembawaan" value="Santai & Menggugah Selera" class="peer sr-only" required>
                                <div class="p-3 sm:p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-smile text-orange-500 mr-3 text-lg sm:text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm sm:text-base">Santai & Menggugah Selera</p>
                                            <p class="text-xs text-gray-600">Tone casual, ramah, dan mengundang</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="gaya_pembawaan" value="Profesional & Informatif" class="peer sr-only">
                                <div class="p-3 sm:p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-briefcase text-blue-500 mr-3 text-lg sm:text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm sm:text-base">Profesional & Informatif</p>
                                            <p class="text-xs text-gray-600">Tone formal, edukatif, dan terpercaya</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="gaya_pembawaan" value="Enerjik & Ceria" class="peer sr-only">
                                <div class="p-3 sm:p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-bolt text-yellow-500 mr-3 text-lg sm:text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm sm:text-base">Enerjik & Ceria</p>
                                            <p class="text-xs text-gray-600">Tone dinamis, semangat, dan menghibur</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="gaya_pembawaan" value="Tenang & Inspiratif" class="peer sr-only">
                                <div class="p-3 sm:p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300">
                                    <div class="flex items-center">
                                        <i class="fas fa-leaf text-green-500 mr-3 text-lg sm:text-xl"></i>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm sm:text-base">Tenang & Inspiratif</p>
                                            <p class="text-xs text-gray-600">Tone menenangkan, motivatif, dan mendalam</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Target Durasi -->
                    <div class="mb-6 sm:mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-3 sm:mb-4">
                            <i class="fas fa-clock mr-2 text-purple-500"></i>
                            Target Durasi Total
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                            <label class="relative">
                                <input type="radio" name="target_durasi" value="15 detik" class="peer sr-only" required>
                                <div class="p-4 sm:p-6 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300 text-center">
                                    <i class="fas fa-flash text-orange-500 text-xl sm:text-2xl mb-2"></i>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base">15 Detik</p>
                                    <p class="text-xs text-gray-600">Quick & Punchy</p>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="target_durasi" value="30 detik" class="peer sr-only">
                                <div class="p-4 sm:p-6 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300 text-center">
                                    <i class="fas fa-play-circle text-purple-500 text-xl sm:text-2xl mb-2"></i>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base">30 Detik</p>
                                    <p class="text-xs text-gray-600">Balanced</p>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="target_durasi" value="60 detik" class="peer sr-only">
                                <div class="p-4 sm:p-6 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-100 peer-checked:to-purple-200 peer-checked:shadow-lg peer-checked:scale-105 hover:border-purple-400 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:shadow-md hover:scale-102 transition-all duration-300 text-center">
                                    <i class="fas fa-video text-green-500 text-xl sm:text-2xl mb-2"></i>
                                    <p class="font-bold text-gray-800 text-sm sm:text-base">60 Detik</p>
                                    <p class="text-xs text-gray-600">Detailed</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Penyebutan Audiens -->
                    <div class="mb-6 sm:mb-8">
                        <label for="penyebutan_audiens" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-users mr-2 text-purple-500"></i>
                            Penyebutan Audiens
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="penyebutan_audiens"
                                   name="penyebutan_audiens"
                                   placeholder="Contoh: Teman-teman, Sobat entrepreneur, Para ibu rumah tangga, dll."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-quote-right text-gray-400"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Masukkan cara Anda menyapa target audiens dalam video</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-center pt-4 sm:pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                        <a href="{{ route('front.content.form', $session->id) }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-semibold transition-all duration-300 text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Kalender
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 sm:px-8 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg font-bold hover:from-orange-600 hover:to-red-700 transform hover:scale-105 transition-all duration-300 shadow-lg text-sm sm:text-base">
                            <i class="fas fa-magic mr-2"></i>
                            Generate Shooting Script
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Tips -->
            <div class="mt-6 sm:mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 sm:p-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1 flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-600 text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-blue-800 mb-2 text-sm sm:text-base">Tips Shooting Script</h4>
                        <ul class="text-blue-700 text-xs sm:text-sm space-y-1">
                            <li>• Pilih gaya pembawaan yang sesuai dengan brand personality Anda</li>
                            <li>• Durasi video sebaiknya disesuaikan dengan platform yang akan digunakan</li>
                            <li>• Penyebutan audiens yang tepat akan meningkatkan engagement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal for Shooting Script Generation -->
    <div id="shooting-script-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center modal-hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center shadow-2xl">
            <div class="mb-6">
                <div class="w-16 h-16 mx-auto mb-4">
                    <div class="shooting-loading-spinner"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Menyusun Shooting Script</h3>
                <p class="text-gray-600 text-sm">AI sedang membuat script yang detail dan menarik untuk video Anda...</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="shooting-loading-progress bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full"></div>
            </div>
            <div class="text-xs text-gray-500" id="shooting-progress-text">Menganalisa konten...</div>

            <!-- Progress Steps -->
            <div class="mt-4 space-y-2 text-left">
                <div class="flex items-center text-xs" id="step-1">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2 flex-shrink-0"></div>
                    <span class="text-gray-500">Menyusun struktur script</span>
                </div>
                <div class="flex items-center text-xs" id="step-2">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2 flex-shrink-0"></div>
                    <span class="text-gray-500">Menentukan timing dan transisi</span>
                </div>
                <div class="flex items-center text-xs" id="step-3">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2 flex-shrink-0"></div>
                    <span class="text-gray-500">Mengoptimalkan engagement</span>
                </div>
                <div class="flex items-center text-xs" id="step-4">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2 flex-shrink-0"></div>
                    <span class="text-gray-500">Finalizing shooting script</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced radio button animations with purple theme */
        input[type="radio"]:checked + div {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(147, 51, 234, 0.15);
        }

        /* Hover scaling */
        .hover\:scale-102:hover {
            transform: scale(1.02);
        }

        /* Checked scaling */
        .peer-checked\:scale-105:checked + div {
            transform: scale(1.05);
        }

        /* Purple gradient backgrounds */
        .hover\:from-purple-50:hover {
            --tw-gradient-from: #faf5ff;
        }

        .hover\:to-purple-100:hover {
            --tw-gradient-to: #f3e8ff;
        }

        .peer-checked\:from-purple-100:checked + div {
            --tw-gradient-from: #f3e8ff;
        }

        .peer-checked\:to-purple-200:checked + div {
            --tw-gradient-to: #e9d5ff;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus styles */
        input:focus {
            outline: none;
        }

        /* Button hover effects */
        button:hover, a:hover {
            transform: translateY(-1px);
        }

        /* Additional shadow for checked state */
        .peer-checked\:shadow-lg:checked + div {
            box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.1), 0 4px 6px -2px rgba(147, 51, 234, 0.05);
        }

        /* Hover shadow with purple tint */
        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(147, 51, 234, 0.1), 0 2px 4px -1px rgba(147, 51, 234, 0.06);
        }

        /* Mobile touch optimization */
        @media (max-width: 640px) {
            .peer:checked + div {
                transform: scale(1.02);
            }

            .hover\:scale-102:hover {
                transform: scale(1.01);
            }
        }

        /* Prevent text selection on radio labels */
        label {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Touch friendly sizing */
        @media (max-width: 640px) {
            label > div {
                min-height: 60px;
            }
        }

        /* Loading Modal for Shooting Script */
        .modal-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            z-index: 9999;
            transition: all 0.3s ease-in-out;
        }

        .modal-show {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: all !important;
        }

        /* Shooting Script Loading Spinner */
        .shooting-loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #f97316;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            position: relative;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Shooting Script Progress Bar */
        .shooting-loading-progress {
            width: 0%;
            transition: width 6s ease-in-out;
        }

        /* Modal Animation */
        .modal-show .bg-white {
            animation: modalBounceIn 0.6s ease-out forwards;
        }

        @keyframes modalBounceIn {
            0% {
                transform: scale(0.3) translateY(-50px);
                opacity: 0;
            }
            50% {
                transform: scale(1.05) translateY(0);
                opacity: 0.8;
            }
            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        /* Simple video icon if Font Awesome fails */
        .shooting-loading-spinner::before {
            content: '▶';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #f97316;
            font-size: 20px;
            animation: videoPulse 1.5s ease-in-out infinite alternate;
        }

        @keyframes videoPulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.8;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 1;
            }
        }

        /* Pulse effect */
        .shooting-loading-spinner::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            width: 72px;
            height: 72px;
            border: 2px solid rgba(249, 115, 22, 0.2);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
        }

        /* Progress steps animation */
        .progress-step-active {
            color: #f97316 !important;
        }

        .progress-step-active .w-3 {
            background-color: #f97316 !important;
            animation: stepGlow 0.8s ease-in-out;
        }

        @keyframes stepGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
                transform: scale(1);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(249, 115, 22, 0);
                transform: scale(1.1);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
                transform: scale(1);
            }
        }

        /* Force modal to show when debugging */
        .force-show {
            opacity: 1 !important;
            visibility: visible !important;
            display: flex !important;
            z-index: 99999 !important;
            background: rgba(0,0,0,0.8) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Script starting...');

            const modal = document.getElementById('shooting-script-modal');
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('button[type="submit"]');

            console.log('Modal element:', modal);
            console.log('Form element:', form);
            console.log('Submit button:', submitBtn);

            // Simple function to show modal
            function showModal() {
                console.log('📱 Showing modal...');

                if (!modal) {
                    console.error('❌ Modal not found!');
                    return;
                }

                // Force show with multiple approaches
                modal.classList.remove('modal-hidden');
                modal.classList.add('modal-show', 'force-show');
                modal.style.display = 'flex';
                modal.style.opacity = '1';
                modal.style.visibility = 'visible';
                modal.style.zIndex = '99999';

                document.body.style.overflow = 'hidden';

                console.log('✅ Modal should be visible now');

                // Start progress animation
                const progressBar = modal.querySelector('.shooting-loading-progress');
                const progressText = modal.querySelector('#shooting-progress-text');

                setTimeout(() => {
                    if (progressBar) {
                        progressBar.style.width = '95%';
                        console.log('📊 Progress bar animated');
                    }
                }, 200);

                // Update text steps
                const steps = [
                    { time: 0, text: 'Menganalisa konten...', step: 1 },
                    { time: 1200, text: 'Menyusun struktur script...', step: 2 },
                    { time: 2800, text: 'Menentukan timing dan transisi...', step: 3 },
                    { time: 4200, text: 'Mengoptimalkan engagement...', step: 4 },
                    { time: 5800, text: 'Finalizing shooting script...', step: null }
                ];

                steps.forEach(({ time, text, step }) => {
                    setTimeout(() => {
                        if (progressText) {
                            progressText.textContent = text;
                        }

                        if (step) {
                            const stepEl = document.getElementById(`step-${step}`);
                            if (stepEl) {
                                stepEl.classList.add('progress-step-active');
                                const dot = stepEl.querySelector('.w-3');
                                if (dot) {
                                    dot.style.backgroundColor = '#f97316';
                                    dot.style.transform = 'scale(1.2)';
                                }
                            }
                        }
                    }, time);
                });
            }



            // Form validation function
            function validateForm() {
                const gayaPembawaan = document.querySelector('input[name="gaya_pembawaan"]:checked');
                const targetDurasi = document.querySelector('input[name="target_durasi"]:checked');
                const penyebutanAudiens = document.querySelector('input[name="penyebutan_audiens"]');

                console.log('Form validation:', {
                    gaya: gayaPembawaan ? gayaPembawaan.value : 'MISSING',
                    durasi: targetDurasi ? targetDurasi.value : 'MISSING',
                    audiens: penyebutanAudiens ? penyebutanAudiens.value : 'MISSING'
                });

                if (!gayaPembawaan) {
                    alert('❌ Silakan pilih gaya pembawaan');
                    return false;
                }
                if (!targetDurasi) {
                    alert('❌ Silakan pilih target durasi');
                    return false;
                }
                if (!penyebutanAudiens || !penyebutanAudiens.value.trim()) {
                    alert('❌ Silakan isi penyebutan audiens');
                    if (penyebutanAudiens) penyebutanAudiens.focus();
                    return false;
                }

                return true;
            }

            // Form submission handler
            if (form && submitBtn) {
                // Method 1: Form submit event
                form.addEventListener('submit', function(e) {
                    console.log('📝 Form submit event triggered');

                    if (!validateForm()) {
                        e.preventDefault();
                        return false;
                    }

                    console.log('✅ Form validation passed');

                    // Show modal before form submits
                    showModal();

                    // Update button
                    submitBtn.innerHTML = '<span style="display:inline-block;animation:spin 1s linear infinite;margin-right:8px;">⟳</span>Generating...';
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.7';

                    // Let form submit normally (remove preventDefault)
                    console.log('🚀 Form will submit now...');
                });

                // Method 2: Button click event (backup)
                submitBtn.addEventListener('click', function(e) {
                    console.log('🖱️ Submit button clicked');

                    // Check if form is valid first
                    if (!validateForm()) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }

                    // If validation passes, show modal
                    setTimeout(() => {
                        showModal();
                    }, 100);
                });

                console.log('✅ Form handlers attached');
            } else {
                console.error('❌ Form or submit button not found');
                console.log('Form:', form);
                console.log('Submit button:', submitBtn);
            }

            // Radio button enhancements
            const radioGroups = document.querySelectorAll('input[type="radio"]');
            radioGroups.forEach(radio => {
                radio.addEventListener('change', function() {
                    console.log('📻 Radio changed:', this.name, this.value);

                    // Visual feedback
                    const siblings = document.querySelectorAll(`input[name="${this.name}"]`);
                    siblings.forEach(sibling => {
                        const label = sibling.nextElementSibling;
                        if (sibling !== this && label) {
                            label.style.transform = 'scale(1)';
                            label.style.boxShadow = '';
                        }
                    });

                    const label = this.nextElementSibling;
                    if (label) {
                        label.style.transform = 'scale(1.05)';
                        label.style.transition = 'all 0.3s ease';
                    }
                });
            });

            // Input field enhancements
            const audiensInput = document.getElementById('penyebutan_audiens');
            if (audiensInput) {
                audiensInput.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.boxShadow = '0 0 0 3px rgba(147, 51, 234, 0.1)';
                });

                audiensInput.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = '';
                });

                // Character counter
                audiensInput.addEventListener('input', function() {
                    const length = this.value.length;
                    const parent = this.parentElement;

                    const existingCounter = parent.querySelector('.char-counter');
                    if (existingCounter) {
                        existingCounter.remove();
                    }

                    if (length > 0) {
                        const counter = document.createElement('div');
                        counter.className = 'char-counter absolute top-2 right-12 text-xs text-gray-400';
                        counter.textContent = `${length} karakter`;
                        parent.appendChild(counter);
                    }
                });
            }

            console.log('✅ Script initialization complete');
        });
    </script>
@endsection
