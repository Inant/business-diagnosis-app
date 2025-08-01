@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mb-4">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Generate Kalender Konten Bisnis</h1>
                <p class="text-gray-600">Buat kalender konten yang menarik berdasarkan analisa bisnis Anda</p>
            </div>

            <!-- Content Calendar Generator Form -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form method="POST" action="{{ route('front.content.generate', $session->id) }}" id="content-calendar-form">
                    @csrf

                    <!-- Form Header -->
                    <div class="p-8 md:p-12">
                        <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
                            <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                                <!-- SVG Calendar Icon (Modern, Simple) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" fill="none"/>
                                    <path d="M16 3v4M8 3v4M3 9h18" stroke="currentColor"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Generator Kalender Konten</h3>
                                <p class="text-gray-500">Pilih durasi konten yang ingin Anda buat</p>
                            </div>
                        </div>

                        <!-- Days Selection -->
                        <div class="mb-8">
                            <label class="block text-gray-700 font-semibold mb-4" for="days">
                                <i class="fas fa-calendar-day mr-2 text-purple-500"></i>
                                Pilih Jumlah Hari Konten
                            </label>

                            <!-- Mobile: Dropdown -->
                            <div class="block md:hidden">
                                <select id="days" name="days"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-lg">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $i == 7 ? 'selected' : '' }}>
                                            {{ $i }} Hari {{ $i == 7 ? '(Recommended)' : '' }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Desktop: Card Grid -->
                            <div class="hidden md:grid grid-cols-2 lg:grid-cols-5 gap-4">
                                @for($i = 1; $i <= 10; $i++)
                                    <div class="day-option">
                                        <input type="radio" name="days" value="{{ $i }}" id="day_{{ $i }}"
                                               class="hidden" {{ $i == 7 ? 'checked' : '' }}>
                                        <label for="day_{{ $i }}"
                                               class="day-card block p-4 border-2 border-gray-200 rounded-xl text-center cursor-pointer transition-all duration-300 hover:border-purple-300 hover:shadow-md {{ $i == 7 ? 'border-purple-500 bg-purple-50' : '' }}">
                                            <div class="text-2xl font-bold text-gray-800 mb-1">{{ $i }}</div>
                                            <div class="text-sm text-gray-600">Hari</div>
                                            @if($i == 7)
                                                <div class="text-xs text-purple-600 font-semibold mt-1">
                                                    <i class="fas fa-star mr-1"></i>Recommended
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                @endfor
                            </div>

                            <div class="mt-4 p-4 bg-purple-50 border-l-4 border-purple-500 rounded-r-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-purple-500 mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-purple-800 mb-2">Tips Pemilihan Durasi:</h4>
                                        <ul class="text-purple-700 text-sm space-y-1">
                                            <li>• <strong>1-3 Hari:</strong> Cocok untuk campaign singkat atau event khusus</li>
                                            <li>• <strong>7 Hari:</strong> Ideal untuk weekly content planning (Recommended)</li>
                                            <li>• <strong>10 Hari:</strong> Untuk planning jangka menengah dan strategi komprehensif</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tujuan Pembuatan Konten -->
                        <div class="mb-8">
                            <label for="tujuan_pembuatan_konten" class="block text-gray-700 font-semibold mb-4">
                                <i class="fas fa-bullseye mr-2 text-pink-500"></i>
                                Tujuan Pembuatan Konten <span class="text-red-500">*</span>
                            </label>
                            <textarea id="tujuan_pembuatan_konten" name="tujuan_pembuatan_konten"
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-100 transition-all duration-300 text-lg resize-none"
                                      rows="3"
                                      required
                                      placeholder="Contoh: Untuk promo paket bundling menu A dan B."></textarea>
                            <p class="text-gray-500 text-sm mt-2">Jelaskan dengan jelas tujuan Anda membuat kalender konten ini, misal: “Untuk promo paket bundling menu A dan B, atau mengenalkan varian baru pada pelanggan setia.”</p>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-8 p-6 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-800 mb-3">
                                <i class="fas fa-eye mr-2 text-purple-500"></i>
                                Yang Akan Anda Dapatkan:
                            </h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-800">Konten Harian Terstruktur</h5>
                                        <p class="text-gray-600 text-sm">Post untuk setiap hari dengan tema yang berbeda</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                                        <i class="fas fa-target text-orange-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-800">Call-to-Action Ideas</h5>
                                        <p class="text-gray-600 text-sm">Saran CTA untuk meningkatkan engagement</p>
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
                                <span class="text-sm">Kalender akan disesuaikan dengan analisa bisnis Anda</span>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                                <a href="{{ route('front.result', $session->id) }}"
                                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 text-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Hasil
                                </a>
                                <button type="submit" id="generate-btn"
                                        class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300">
                                    <i class="fas fa-magic mr-2"></i>Generate Kalender
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 grid md:grid-cols-2 gap-6">
                <!-- Process Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-cogs text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Proses Generate</h4>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>AI menganalisa hasil diagnosis bisnis Anda</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Menyesuaikan konten dengan target audience</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Menghasilkan kalender yang siap digunakan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Loading Modal for Content Calendar Generation -->
    <div id="calendar-generation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
            <div class="mb-6">
                <div class="w-16 h-16 mx-auto mb-4">
                    <div class="calendar-loading-spinner"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Membuat Kalender Konten</h3>
                <p class="text-gray-600 text-sm">AI sedang menyusun konten yang menarik untuk bisnis Anda...</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="calendar-loading-progress bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full"></div>
            </div>
            <div class="text-xs text-gray-500" id="calendar-progress-text">Menganalisa bisnis Anda...</div>
        </div>
    </div>

    <style>
        /* Custom styles for day selection cards */
        .day-option input[type="radio"]:checked + .day-card {
            border-color: #8b5cf6;
            background-color: #faf5ff;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .day-card:hover {
            transform: translateY(-2px);
        }

        .day-option input[type="radio"]:checked + .day-card .text-gray-800 {
            color: #7c3aed;
        }

        .day-option input[type="radio"]:checked + .day-card .text-gray-600 {
            color: #8b5cf6;
        }

        /* Loading state */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .day-card {
                padding: 1rem;
            }

            .text-4xl {
                font-size: 2.5rem;
            }

            .p-8 {
                padding: 1.5rem;
            }

            .md\:p-12 {
                padding: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .text-4xl {
                font-size: 2rem;
            }

            .px-8 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .py-6 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }

        /* Loading Modal for Calendar Generation */
        #calendar-generation-modal {
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        #calendar-generation-modal.show {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Calendar Loading Spinner */
        .calendar-loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #8b5cf6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            position: relative;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Calendar Loading Progress Bar */
        .calendar-loading-progress {
            width: 0%;
            transition: width 4s ease-in-out;
        }

        /* Modal Animation */
        #calendar-generation-modal.show .bg-white {
            animation: modalBounce 0.5s ease-out;
        }

        @keyframes modalBounce {
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

        /* Pulse effect untuk calendar spinner */
        .calendar-loading-spinner::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            width: 72px;
            height: 72px;
            border: 2px solid rgba(139, 92, 246, 0.2);
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

        /* Calendar icon animation in spinner */
        .calendar-loading-spinner::before {
            content: '\f073';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #8b5cf6;
            font-size: 20px;
            animation: bounce 1s ease-in-out infinite alternate;
        }

        @keyframes bounce {
            0% { transform: translate(-50%, -50%) scale(1); }
            100% { transform: translate(-50%, -50%) scale(1.1); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('content-calendar-form');
            const generateBtn = document.getElementById('generate-btn');
            const dayOptions = document.querySelectorAll('input[name="days"]');
            const mobileSelect = document.getElementById('days');

            // Function to show calendar generation modal
            function showCalendarGenerationModal() {
                const modal = document.getElementById('calendar-generation-modal');
                const progressBar = modal.querySelector('.calendar-loading-progress');
                const progressText = document.getElementById('calendar-progress-text');

                modal.classList.remove('hidden');

                // Force reflow
                modal.offsetHeight;

                modal.classList.add('show');

                // Prevent scrolling
                document.body.style.overflow = 'hidden';

                // Animate progress bar
                setTimeout(() => {
                    progressBar.style.width = '95%';
                }, 100);

                // Update progress text with different messages
                const messages = [
                    'Menganalisa bisnis Anda...',
                    'Menyusun ide konten...',
                    'Membuat strategi posting...',
                    'Mengoptimalkan engagement...',
                    'Finalizing kalender...'
                ];

                let messageIndex = 0;
                const messageInterval = setInterval(() => {
                    if (messageIndex < messages.length) {
                        progressText.textContent = messages[messageIndex];
                        messageIndex++;
                    } else {
                        clearInterval(messageInterval);
                    }
                }, 800);

                // Prevent modal close
                modal.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
            }

            // Sync mobile select with desktop radio buttons
            if (mobileSelect) {
                mobileSelect.addEventListener('change', function() {
                    const selectedValue = this.value;
                    dayOptions.forEach(option => {
                        option.checked = option.value === selectedValue;
                    });
                });
            }

            // Sync desktop radio buttons with mobile select
            dayOptions.forEach(option => {
                option.addEventListener('change', function() {
                    if (this.checked && mobileSelect) {
                        mobileSelect.value = this.value;
                    }
                });
            });

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                // Get selected days for personalized message
                const selectedDays = document.querySelector('input[name="days"]:checked').value;

                // Show loading modal
                showCalendarGenerationModal();

                // Update button state
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
                generateBtn.disabled = true;
                generateBtn.classList.add('opacity-75');

                // Add loading class to form
                form.classList.add('loading');

                console.log('Generating calendar for ' + selectedDays + ' days');
            });

            // Add hover effects for mobile
            if (window.innerWidth <= 768) {
                mobileSelect.addEventListener('focus', function() {
                    this.classList.add('ring-4', 'ring-purple-100');
                });

                mobileSelect.addEventListener('blur', function() {
                    this.classList.remove('ring-4', 'ring-purple-100');
                });
            }

            // Add smooth interactions for day cards
            const dayCards = document.querySelectorAll('.day-card');
            dayCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (!this.previousElementSibling.checked) {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 4px 12px rgba(139, 92, 246, 0.15)';
                    }
                });

                card.addEventListener('mouseleave', function() {
                    if (!this.previousElementSibling.checked) {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '';
                    }
                });
            });
        });
    </script>
@endsection
