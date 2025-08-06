@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-ad text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Generate Iklan</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Kelola dan lihat semua iklan yang telah Anda generate</p>
            </div>

            @if($mainSession)
                <!-- Quick Action - Create New Ads -->
                <div class="mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                        <div class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="text-center sm:text-left mb-4 sm:mb-0">
                                <h3 class="text-lg sm:text-xl font-semibold mb-2">Generate Iklan Baru</h3>
                                <p class="text-sm sm:text-base text-blue-100">
                                    Buat strategi iklan baru berdasarkan analisa bisnis yang sudah ada
                                </p>
                            </div>
                            <a href="{{ route('front.ads.create') }}"
                               class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-all duration-300 text-sm sm:text-base shadow-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Generate Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                @if($adsPlans->count())
                    <!-- Ads Plans Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                        @foreach($adsPlans as $index => $adsPlan)
                            @php
                                $planNumber = $adsPlans->count() - $index;

                                // Platform colors
                                $platformColors = [
                                    'facebook_instagram' => [
                                        'gradient' => 'from-blue-500 to-blue-600',
                                        'hover' => 'hover:from-blue-600 hover:to-blue-700',
                                        'icon_bg' => 'bg-blue-100',
                                        'icon_color' => 'text-blue-800',
                                        'badge_bg' => 'bg-blue-100',
                                        'badge_color' => 'text-blue-800',
                                        'footer_gradient' => 'from-blue-50 to-blue-100',
                                        'footer_text' => 'text-blue-600',
                                        'stats_color' => 'text-blue-500'
                                    ],
                                    'tiktok' => [
                                        'gradient' => 'from-red-500 to-pink-600',
                                        'hover' => 'hover:from-red-600 hover:to-pink-700',
                                        'icon_bg' => 'bg-red-100',
                                        'icon_color' => 'text-red-800',
                                        'badge_bg' => 'bg-red-100',
                                        'badge_color' => 'text-red-800',
                                        'footer_gradient' => 'from-red-50 to-pink-50',
                                        'footer_text' => 'text-red-600',
                                        'stats_color' => 'text-red-500'
                                    ],
                                    'google_search' => [
                                        'gradient' => 'from-green-500 to-emerald-600',
                                        'hover' => 'hover:from-green-600 hover:to-emerald-700',
                                        'icon_bg' => 'bg-green-100',
                                        'icon_color' => 'text-green-800',
                                        'badge_bg' => 'bg-green-100',
                                        'badge_color' => 'text-green-800',
                                        'footer_gradient' => 'from-green-50 to-emerald-50',
                                        'footer_text' => 'text-green-600',
                                        'stats_color' => 'text-green-500'
                                    ]
                                ];

                                $colors = $platformColors[$adsPlan->platform] ?? $platformColors['facebook_instagram'];
                            @endphp

                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                                <!-- Card Header with Platform Color -->
                                <div class="bg-gradient-to-r {{ $colors['gradient'] }} p-4 sm:p-6">
                                    <div class="flex items-center justify-between text-white">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                                <span class="font-bold text-lg sm:text-xl">{{ $planNumber }}</span>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-sm sm:text-base">Iklan Plan #{{ $planNumber }}</h3>
                                                <p class="text-xs sm:text-sm text-white text-opacity-80">{{ $adsPlan->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs sm:text-sm text-white text-opacity-80">{{ $adsPlan->created_at->format('H:i') }} WIB</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-4 sm:p-6">
                                    <!-- Ads Stats -->
                                    <div class="mb-4 sm:mb-6">
                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">
                                            <i class="fas fa-bullhorn mr-2 {{ $colors['stats_color'] }}"></i>
                                            <span>{{ $adsPlan->platform_label }}</span>
                                        </div>
{{--                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">--}}
{{--                                            <i class="fas fa-coins mr-2 text-yellow-500"></i>--}}
{{--                                            <span>{{ $adsPlan->formatted_cost }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm mb-2">--}}
{{--                                            <i class="fas fa-clock mr-2 {{ $colors['stats_color'] }}"></i>--}}
{{--                                            <span>{{ $adsPlan->response_time_ms }}ms response</span>--}}
{{--                                        </div>--}}
                                        <div class="flex items-center text-gray-600 text-xs sm:text-sm">
                                            <i class="fas fa-history mr-2 text-gray-400"></i>
                                            <span>{{ $adsPlan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <!-- Goal & Product -->
                                    <div class="mb-4 sm:mb-6">
                                        <p class="text-xs sm:text-sm text-gray-500 mb-2">Tujuan:</p>
                                        <div class="{{ $colors['icon_bg'] }} border {{ str_replace('100', '200', $colors['badge_bg']) }} rounded-lg p-3 mb-3">
                                            <p class="text-xs sm:text-sm {{ $colors['icon_color'] }} font-medium">
                                                {{ Str::limit($adsPlan->goal, 80) }}
                                            </p>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-500 mb-2">Produk:</p>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                            <p class="text-xs sm:text-sm text-gray-700 font-medium">
                                                {{ Str::limit($adsPlan->product, 80) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Platform Badge -->
                                    <div class="mb-4 sm:mb-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colors['badge_bg'] }} {{ $colors['badge_color'] }}">
                                            <i class="fas fa-{{ $adsPlan->platform == 'facebook_instagram' ? 'facebook' : ($adsPlan->platform == 'tiktok' ? 'tiktok' : 'google') }} mr-1"></i>
                                            {{ $adsPlan->platform_label }}
                                        </span>
                                    </div>

                                    <!-- Action Button -->
                                    <div>
                                        <a href="{{ route('front.ads.detail', $adsPlan->ads_plan_id) }}"
                                           class="w-full flex items-center justify-center px-4 py-2 sm:py-3 bg-gradient-to-r {{ $colors['gradient'] }} text-white rounded-lg font-semibold {{ $colors['hover'] }} transition-all duration-300 text-xs sm:text-sm transform hover:scale-105">
                                            <i class="fas fa-eye mr-2"></i>
                                            Lihat Detail Iklan
                                        </a>
                                    </div>
                                </div>

                                <!-- Card Footer with Platform Color -->
                                <div class="bg-gradient-to-r {{ $colors['footer_gradient'] }} px-4 py-3 sm:px-6 sm:py-4">
                                    <div class="flex items-center justify-between text-xs sm:text-sm">
                                        <span class="flex items-center {{ $colors['footer_text'] }}">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $adsPlan->created_at->format('d F Y') }}
                                        </span>
                                        <span class="flex items-center {{ $colors['footer_text'] }}">
                                            <i class="fas fa-ad mr-1"></i>
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
                            <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>
                            Statistik Generate Iklan
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-1">{{ $adsPlans->count() }}</div>
                                <div class="text-xs sm:text-sm text-gray-600">Total Plan</div>
                            </div>

                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-yellow-600 mb-1">
                                    {{ $adsPlans->where('created_at', '>=', now()->subDays(30))->count() }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-600">Bulan Ini</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-xl sm:text-2xl font-bold text-purple-600 mb-1">
                                    {{ $adsPlans->groupBy('platform')->count() }}
                                </div>
                                <div class="text-xs sm:text-sm text-gray-600">Platform</div>
                            </div>
                        </div>

                        <!-- Platform Distribution -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h5 class="font-semibold text-gray-800 mb-3 text-sm">Distribusi Platform:</h5>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @php
                                    $platformCounts = $adsPlans->groupBy('platform');
                                @endphp

                                <div class="text-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                        <span class="text-sm font-medium text-blue-800">Facebook & Instagram</span>
                                    </div>
                                    <div class="text-lg font-bold text-blue-600">{{ $platformCounts->get('facebook_instagram', collect())->count() }}</div>
                                </div>

                                <div class="text-center p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fab fa-tiktok text-red-600 mr-2"></i>
                                        <span class="text-sm font-medium text-red-800">TikTok</span>
                                    </div>
                                    <div class="text-lg font-bold text-red-600">{{ $platformCounts->get('tiktok', collect())->count() }}</div>
                                </div>

                                <div class="text-center p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-center mb-2">
                                        <i class="fab fa-google text-green-600 mr-2"></i>
                                        <span class="text-sm font-medium text-green-800">Google Search</span>
                                    </div>
                                    <div class="text-lg font-bold text-green-600">{{ $platformCounts->get('google_search', collect())->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Empty State for Ads Plans -->
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8">
                        <div class="p-8 sm:p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-full mb-4 sm:mb-6">
                                <i class="fas fa-bullhorn text-blue-400 text-2xl sm:text-3xl"></i>
                            </div>

                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3">Siap Generate Iklan Pertama?</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 max-w-md mx-auto">
                                Analisa bisnis Anda sudah lengkap! Sekarang saatnya membuat strategi iklan yang efektif berdasarkan data bisnis Anda.
                            </p>

                            <a href="{{ route('front.ads.create') }}"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 text-sm sm:text-base transform hover:scale-105 shadow-lg">
                                <i class="fas fa-ad mr-2"></i>
                                Generate Iklan Pertama
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
                        <a href="{{ route('front.ads.create') }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-plus mr-2"></i>
                            Generate Iklan Baru
                        </a>
                        <a href="{{ route('front.content.history') }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-purple-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-magic mr-2"></i>
                            Generate Konten
                        </a>
                        <a href="{{ route('front.result', $mainSession->id) }}"
                           class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-300 text-xs sm:text-sm">
                            <i class="fas fa-chart-line mr-2"></i>
                            Lihat Analisa Bisnis
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
                            Untuk dapat generate iklan, Anda perlu melengkapi analisa bisnis dan SWOT terlebih dahulu. Proses ini hanya dilakukan sekali!
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
                                    <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center mr-3">3</div>
                                    <span>Generate Iklan</span>
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
        .hover\:shadow-2xl:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-0.25rem);
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add intersection observer for animations
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
            document.querySelectorAll('.bg-white').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(el);
            });
        });
    </script>
@endsection
