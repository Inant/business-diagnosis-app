@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-magic text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Generate Konten</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Kelola dan lihat semua konten yang telah Anda generate</p>
            </div>

            @if($mainSession)
                <!-- Session Info Card -->
                <div class="mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                        <div class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="text-center sm:text-left mb-4 sm:mb-0">
                                <h3 class="text-lg sm:text-xl font-semibold mb-1">Sesi Analisa Bisnis Anda</h3>
                                <p class="text-sm sm:text-base text-blue-100">
                                    Dibuat pada {{ $mainSession->created_at->format('d F Y') }} - {{ $mainSession->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('front.result', $mainSession->id) }}"
                                   class="inline-flex items-center px-3 py-2 bg-white bg-opacity-20 text-white font-semibold rounded-lg hover:bg-opacity-30 transition-all duration-300 text-xs sm:text-sm">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    Analisa
                                </a>
                                <a href="{{ route('front.swot.form', $mainSession->id) }}"
                                   class="inline-flex items-center px-3 py-2 bg-white bg-opacity-20 text-white font-semibold rounded-lg hover:bg-opacity-30 transition-all duration-300 text-xs sm:text-sm">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    SWOT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action - Create New Content -->
                <div class="mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                        <div class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="text-center sm:text-left mb-4 sm:mb-0">
                                <h3 class="text-lg sm:text-xl font-semibold mb-2">Generate Konten Baru</h3>
                                <p class="text-sm sm:text-base text-pink-100">
                                    Buat ide konten baru berdasarkan analisa bisnis yang sudah ada
                                </p>
                            </div>
                            <a href="{{ route('front.content.create') }}"
                               class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-white text-pink-600 font-semibold rounded-lg hover:bg-pink-50 transition-all duration-300 text-sm sm:text-base shadow-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Generate Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                @if($contentPlans->count())
                    <!-- Content Plans Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                        @foreach($contentPlans as $index => $contentPlan)
                            @php
                                $planNumber = $contentPlans->count() - $index;
                            @endphp

                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                <!-- Card Header -->
                                <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-4 sm:p-6">
                                    <div class="flex items-center justify-between text-white">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                                <span class="font-bold text-lg sm:text-xl">{{ $planNumber }}</span>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-sm sm:text-base">Konten Plan #{{ $planNumber }}</h3>
                                                <p class="text-xs sm:text-sm text-pink-100">{{ $contentPlan->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs sm:text-sm text-pink-100">{{ $contentPlan->created_at->format('H:i') }} WIB</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-4 sm:p-6">
                                    <!-- Content Stats -->
                                    <div class="mb-4 sm:mb-6">
                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">
                                            <i class="fas fa-calendar-alt mr-2 text-pink-500"></i>
                                            <span>{{ $contentPlan->content_count }} Ide Konten ({{ $contentPlan->days }} hari)</span>
                                        </div>
                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">
                                            <i class="fas fa-coins mr-2 text-yellow-500"></i>
                                            <span>{{ $contentPlan->formatted_cost }}</span>
                                        </div>
{{--                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">--}}
{{--                                            <i class="fas fa-clock mr-2 text-blue-500"></i>--}}
{{--                                            <span>{{ $contentPlan->response_time_ms }}ms response</span>--}}
{{--                                        </div>--}}
                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm">
                                            <i class="fas fa-history mr-2 text-gray-400"></i>
                                            <span>{{ $contentPlan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Tujuan Konten -->
                                    @if($contentPlan->tujuan_pembuatan_konten)
                                        <div class="mb-4 sm:mb-6">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-2">Tujuan Konten:</p>
                                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-3">
                                                <p class="text-xs sm:text-sm text-pink-700 font-medium">
                                                    {{ Str::limit($contentPlan->tujuan_pembuatan_konten, 120) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Content Preview -->
                                    <div class="mb-4 sm:mb-6">
                                        <p class="text-xs sm:text-sm text-gray-500 mb-2">Preview Konten:</p>
                                        <div class="grid grid-cols-3 gap-1">
                                            @for($i = 1; $i <= min(6, $contentPlan->content_count); $i++)
                                                <div class="bg-gradient-to-br from-pink-100 to-purple-100 rounded-lg h-8 sm:h-10 flex items-center justify-center transform hover:scale-105 transition-transform duration-200">
                                                    <span class="text-xs font-semibold text-pink-600">{{ $i }}</span>
                                                </div>
                                            @endfor
                                        </div>
                                        @if($contentPlan->content_count > 6)
                                            <p class="text-xs text-gray-500 mt-2 text-center">+{{ $contentPlan->content_count - 6 }} konten lainnya</p>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div>
                                        <a href="{{ route('front.content.detail', $contentPlan->content_plan_id) }}"
                                           class="w-full flex items-center justify-center px-4 py-2 sm:py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg font-semibold hover:from-pink-600 hover:to-purple-700 transition-all duration-300 text-xs sm:text-sm transform hover:scale-105">
                                            <i class="fas fa-eye mr-2"></i>
                                            Lihat Detail Konten
                                        </a>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="bg-gradient-to-r from-pink-50 to-purple-50 px-4 py-3 sm:px-6 sm:py-4">
                                    <div class="flex items-center justify-between text-xs sm:text-sm">
                                        <span class="flex items-center text-pink-600">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $contentPlan->created_at->format('d F Y') }}
                                        </span>
                                        <span class="flex items-center text-purple-600">
                                            <i class="fas fa-magic mr-1"></i>
                                            Plan #{{ $planNumber }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
                        <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-sm sm:text-base flex items-center">
                            <i class="fas fa-chart-pie mr-2 text-purple-500"></i>
                            Statistik Generate Konten
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-pink-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-pink-600 mb-1">{{ $contentPlans->count() }}</div>
                                <div class="text-xs sm:text-sm text-gray-600">Total Plan</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-purple-600 mb-1">{{ $contentPlans->sum('content_count') }}</div>
                                <div class="text-xs sm:text-sm text-gray-600">Total Konten</div>
                            </div>
{{--                            <div class="text-center p-3 bg-blue-50 rounded-lg">--}}
{{--                                <div class="text-lg sm:text-xl font-bold text-blue-600 mb-1">--}}
{{--                                    Rp {{ number_format($contentPlans->sum('cost_idr'), 0, ',', '.') }}--}}
{{--                                </div>--}}
{{--                                <div class="text-xs sm:text-sm text-gray-600">Total Biaya</div>--}}
{{--                            </div>--}}
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-green-600 mb-1">
                                    {{ $contentPlans->where('created_at', '>=', now()->subDays(30))->count() }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-600">Bulan Ini</div>
                            </div>
                        </div>

                        <!-- Additional Stats -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Rata-rata Konten/Plan</div>
                                    <div class="text-lg font-bold text-indigo-600">
                                        {{ $contentPlans->count() > 0 ? round($contentPlans->sum('content_count') / $contentPlans->count()) : 0 }}
                                    </div>
                                </div>
{{--                                <div>--}}
{{--                                    <div class="text-sm font-semibold text-gray-700">Rata-rata Biaya/Plan</div>--}}
{{--                                    <div class="text-lg font-bold text-indigo-600">--}}
{{--                                        Rp {{ $contentPlans->count() > 0 ? number_format($contentPlans->sum('cost_idr') / $contentPlans->count(), 0, ',', '.') : 0 }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">Plan Terbaru</div>
                                    <div class="text-lg font-bold text-indigo-600">
                                        {{ $contentPlans->first() ? $contentPlans->first()->created_at->diffForHumans() : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Empty State for Content Plans -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8">
                        <div class="p-8 sm:p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-pink-100 rounded-full mb-4 sm:mb-6">
                                <i class="fas fa-lightbulb text-pink-400 text-2xl sm:text-3xl"></i>
                            </div>

                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Siap Generate Konten Pertama?</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                                Analisa bisnis Anda sudah lengkap! Sekarang saatnya membuat konten yang menarik berdasarkan data bisnis Anda.
                            </p>

                            <a href="{{ route('front.content.create') }}"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-semibold rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-300 text-sm sm:text-base transform hover:scale-105 shadow-lg">
                                <i class="fas fa-magic mr-2"></i>
                                Generate Konten Pertama
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-sm sm:text-base flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        Aksi Cepat
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <a href="{{ route('front.content.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg font-semibold hover:from-pink-600 hover:to-pink-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Generate Konten Baru
                        </a>
                        <a href="{{ route('front.result', $mainSession->id) }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-chart-line mr-2"></i>
                            Lihat Analisa Bisnis
                        </a>
                        <a href="{{ route('front.swot.form', $mainSession->id) }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Lihat SWOT Analysis
                        </a>
                    </div>
                </div>

            @else
                <!-- Empty State - No Analysis Yet -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full mb-4 sm:mb-6">
                            <i class="fas fa-chart-line text-gray-400 text-2xl sm:text-3xl"></i>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Analisa Bisnis Diperlukan</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                            Untuk dapat generate konten, Anda perlu melengkapi analisa bisnis dan SWOT terlebih dahulu. Proses ini hanya dilakukan sekali!
                        </p>

                        <!-- Steps -->
                        <div class="mb-6 sm:mb-8">
                            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center mr-3">1</div>
                                    <span>Analisa Bisnis</span>
                                </div>
                                <div class="hidden sm:block">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center mr-3">2</div>
                                    <span>SWOT Analysis</span>
                                </div>
                                <div class="hidden sm:block">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center mr-3">3</div>
                                    <span>Generate Konten</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('front.form') }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm sm:text-base transform hover:scale-105 shadow-lg">
                            <i class="fas fa-play mr-2"></i>
                            Mulai Analisa Bisnis
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom hover effects */
        .hover\:shadow-2xl:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-0.25rem);
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Content preview animation */
        .grid > div {
            animation: fadeInUp 0.3s ease forwards;
        }

        .grid > div:nth-child(2) { animation-delay: 0.1s; }
        .grid > div:nth-child(3) { animation-delay: 0.2s; }
        .grid > div:nth-child(4) { animation-delay: 0.3s; }
        .grid > div:nth-child(5) { animation-delay: 0.4s; }
        .grid > div:nth-child(6) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading button animation */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive grid improvements */
        @media (max-width: 640px) {
            .grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .grid-cols-3 > div {
                height: 2rem;
            }
        }

        /* Card hover improvements */
        .transform:hover {
            transform: translateY(-4px) scale(1.02);
        }

        /* Gradient text effect */
        .gradient-text {
            background: linear-gradient(45deg, #ec4899, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click animation to cards
            const cards = document.querySelectorAll('.bg-white.rounded-xl, .bg-white.rounded-2xl');
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target === this || e.target.closest('a, button') === null) {
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    }
                });
            });

            // Add loading state to buttons
            const buttons = document.querySelectorAll('a[href]');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.href && !this.href.includes('#') && !this.target) {
                        // Add loading class
                        this.classList.add('btn-loading');
                        this.style.opacity = '0.7';

                        // Find the icon and change to spinner
                        const icon = this.querySelector('i');
                        if (icon && !icon.classList.contains('fa-spinner')) {
                            icon.className = 'fas fa-spinner fa-spin mr-2';
                        }

                        // Add loading text
                        const textElements = this.childNodes;
                        textElements.forEach(node => {
                            if (node.nodeType === 3 && node.textContent.trim()) { // Text node
                                node.textContent = 'Loading...';
                            }
                        });
                    }
                });
            });

            // Add animation delay to grid items
            const gridItems = document.querySelectorAll('.grid > div');
            gridItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });

            // Add intersection observer for scroll animations
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

            // Observe cards for scroll animation
            const animatedElements = document.querySelectorAll('.bg-white');
            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });

            // Add tooltip functionality
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-50';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    tooltip.style.bottom = '100%';
                    tooltip.style.left = '50%';
                    tooltip.style.transform = 'translateX(-50%)';
                    tooltip.style.marginBottom = '5px';
                    this.style.position = 'relative';
                    this.appendChild(tooltip);
                });

                element.addEventListener('mouseleave', function() {
                    const tooltip = this.querySelector('.absolute.bg-gray-800');
                    if (tooltip) {
                        tooltip.remove();
                    }
                });
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open modals or overlays
                document.querySelectorAll('.modal, .overlay').forEach(el => {
                    el.style.display = 'none';
                });
            }
        });
    </script>
@endsection
