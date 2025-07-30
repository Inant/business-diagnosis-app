@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-history text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Riwayat Analisa Bisnis</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Lihat kembali semua analisa bisnis yang pernah Anda lakukan</p>
            </div>

            @if($sessions->count())
                <!-- Sessions Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($sessions as $session)
                        @php
                            $diagnosis = $session->aiResponses->firstWhere('step', 'diagnosis');
                            $swot = $session->aiResponses->firstWhere('step', 'swot');
                            $content = $session->aiResponses->firstWhere('step', 'content_plan');
                            $answersCount = $session->userAnswers()->count();
                        @endphp

                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-chart-line text-lg sm:text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-sm sm:text-base">Sesi Analisa</h3>
                                            <p class="text-xs sm:text-sm text-purple-100">{{ $session->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs sm:text-sm text-purple-100">{{ $session->created_at->format('H:i') }} WIB</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-4 sm:p-6">
                                <!-- Stats -->
                                <div class="mb-4 sm:mb-6">
                                    <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        <span>{{ $answersCount }} Pertanyaan Dijawab</span>
                                    </div>
                                    <div class="flex items-center text-gray-600 text-xs sm:text-sm">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>{{ $session->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="mb-4 sm:mb-6">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-2 sm:mb-3">Status Analisa:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Analisa Awal
                                        </span>
                                        @if($swot)
                                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                SWOT
                                            </span>
                                        @endif
                                        @if($content)
                                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full bg-pink-100 text-pink-700 text-xs font-medium">
                                                <i class="fas fa-magic mr-1"></i>
                                                Konten
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2 sm:space-y-3">
                                    <a href="{{ route('front.result', $session->id) }}"
                                       class="w-full flex items-center justify-center px-4 py-2 sm:py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-xs sm:text-sm">
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat Analisa Awal
                                    </a>

                                    <div class="grid grid-cols-2 gap-2">
                                        @if($swot)
                                            <a href="{{ route('front.swot.form', $session->id) }}"
                                               class="flex items-center justify-center px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 text-xs">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                <span class="hidden sm:inline">Lihat </span>SWOT
                                            </a>
                                        @else
                                            <button class="flex items-center justify-center px-3 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed text-xs" disabled>
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                <span class="hidden sm:inline">Buat </span>SWOT
                                            </button>
                                        @endif

                                        @if($content)
                                            <a href="{{ route('front.content.form', $session->id) }}"
                                               class="flex items-center justify-center px-3 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg font-semibold hover:from-pink-600 hover:to-pink-700 transition-all duration-300 text-xs">
                                                <i class="fas fa-magic mr-1"></i>
                                                <span class="hidden sm:inline">Lihat </span>Konten
                                            </a>
                                        @else
                                            <button class="flex items-center justify-center px-3 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed text-xs" disabled>
                                                <i class="fas fa-magic mr-1"></i>
                                                <span class="hidden sm:inline">Buat </span>Konten
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:py-4">
                                <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $session->created_at->format('d F Y') }}
                                    </span>
                                    @if($swot && $content)
                                        <span class="flex items-center text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="flex items-center text-orange-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            Sebagian
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <!-- Empty State Icon -->
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full mb-4 sm:mb-6">
                            <i class="fas fa-chart-line text-gray-400 text-2xl sm:text-3xl"></i>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Belum Ada Riwayat Analisa</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                            Anda belum pernah melakukan analisa bisnis. Mulai analisa pertama Anda sekarang untuk mendapatkan insight yang valuable!
                        </p>

                        <a href="{{ route('front.form') }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-semibold rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-300 text-sm sm:text-base">
                            <i class="fas fa-plus mr-2"></i>
                            Mulai Analisa Bisnis
                        </a>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
{{--            <div class="mt-6 sm:mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">--}}
{{--                <div class="text-center">--}}
{{--                    <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-sm sm:text-base">Aksi Cepat</h4>--}}
{{--                    <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">--}}
{{--                        <a href="{{ route('front.form') }}"--}}
{{--                           class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-xs sm:text-sm">--}}
{{--                            <i class="fas fa-plus mr-2"></i>Analisa Bisnis Baru--}}
{{--                        </a>--}}
{{--                        <a href="{{ route('dashboard') }}"--}}
{{--                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                            <i class="fas fa-home mr-2"></i>Kembali ke Dashboard--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Statistics Card -->
            @if($sessions->count())
                <div class="mt-6 sm:mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-sm sm:text-base">Statistik Analisa Anda</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ $sessions->count() }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Total Analisa</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $sessions->filter(function($session) { return $session->aiResponses->firstWhere('step', 'swot'); })->count() }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">SWOT Selesai</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-pink-600">{{ $sessions->filter(function($session) { return $session->aiResponses->firstWhere('step', 'content_plan'); })->count() }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Konten Plan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl sm:text-2xl font-bold text-purple-600">{{ $sessions->sum(function($session) { return $session->userAnswers()->count(); }) }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Total Jawaban</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Responsive breakpoint for extra small screens */
        @media (min-width: 475px) {
            .xs\:inline { display: inline !important; }
        }

        /* Custom hover effects */
        .hover\:shadow-2xl:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-0.25rem);
        }

        /* Smooth transitions */
        * {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
    </style>

    <script>
        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add click animation to cards
            const cards = document.querySelectorAll('.bg-white.rounded-xl');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Only if clicking on the card itself, not buttons
                    if (e.target === this || e.target.closest('a, button') === null) {
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    }
                });
            });

            // Add loading state to buttons
            const buttons = document.querySelectorAll('a[href], button');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled && this.href) {
                        this.style.opacity = '0.7';
                        this.innerHTML += ' <i class="fas fa-spinner fa-spin ml-2"></i>';
                    }
                });
            });
        });
    </script>
@endsection
