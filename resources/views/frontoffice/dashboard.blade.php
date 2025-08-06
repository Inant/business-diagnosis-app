@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4">
            @if($mainSession)
                <!-- Welcome Section -->
                <div class="mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Selamat Datang Kembali!</h1>
                                <p class="text-blue-100">Kelola dan pantau perkembangan bisnis Anda dengan mudah</p>
                            </div>
                            <div class="hidden sm:block">
                                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chart-line text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business DNA Profile Card -->
                @if(isset($profilDna))
                    <div class="mb-6 sm:mb-8">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-dna mr-3"></i>
                                    Profil DNA Bisnis Anda
                                </h3>
                                <p class="text-purple-100 text-sm mt-1">
                                    Analisa terakhir: {{ $mainSession->created_at->format('d F Y') }}
                                </p>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @if(isset($profilDna['Nama_Bisnis']))
                                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                                            <i class="fas fa-store text-purple-500 text-2xl mb-2"></i>
                                            <h4 class="font-semibold text-gray-800 mb-1">Nama Bisnis</h4>
                                            <p class="text-sm text-gray-600">{{ $profilDna['Nama_Bisnis'] }}</p>
                                        </div>
                                    @endif

                                    @if(isset($profilDna['Target_Pasar_Spesifik']))
                                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                                            <i class="fas fa-users text-blue-500 text-2xl mb-2"></i>
                                            <h4 class="font-semibold text-gray-800 mb-1">Target Pasar</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($profilDna['Target_Pasar_Spesifik'], 50) }}</p>
                                        </div>
                                    @endif

                                    @if(isset($profilDna['Kekuatan_Unik_Teridentifikasi']))
                                        <div class="text-center p-4 bg-green-50 rounded-lg">
                                            <i class="fas fa-star text-green-500 text-2xl mb-2"></i>
                                            <h4 class="font-semibold text-gray-800 mb-1">Kekuatan Unik</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($profilDna['Kekuatan_Unik_Teridentifikasi'], 50) }}</p>
                                        </div>
                                    @endif

                                    @if(isset($profilDna['Visi_Jangka_Panjang']))
                                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                                            <i class="fas fa-rocket text-orange-500 text-2xl mb-2"></i>
                                            <h4 class="font-semibold text-gray-800 mb-1">Visi</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($profilDna['Visi_Jangka_Panjang'], 50) }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('front.result', $mainSession->id) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm">
                                            <i class="fas fa-chart-line mr-2"></i>
                                            Lihat Analisa Lengkap
                                        </a>
                                        <a href="{{ route('front.swot.form', $mainSession->id) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 text-sm">
                                            <i class="fas fa-chart-bar mr-2"></i>
                                            SWOT Analysis
                                        </a>
                                        <a href="{{ route('front.content.create') }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg hover:from-purple-600 hover:to-pink-700 transition-all duration-300 text-sm">
                                            <i class="fas fa-magic mr-2"></i>
                                            Generate Konten
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-lightbulb text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-800">Total Content Plans</h4>
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_content_plans'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-file-alt text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-800">Total Konten</h4>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['total_content_ideas'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <i class="fas fa-calendar text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-800">Bulan Ini</h4>
                                <p class="text-2xl font-bold text-purple-600">{{ $stats['this_month_plans'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($contentPlans && $contentPlans->count() > 0)
                    <!-- Recent Content Plans -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-history mr-3"></i>
                                    Content Plans Terbaru
                                </h3>
                                <a href="{{ route('front.content.history') }}"
                                   class="text-pink-100 hover:text-white text-sm">
                                    Lihat Semua →
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($contentPlans as $index => $contentPlan)
                                    @php
                                        $planNumber = $contentPlans->count() - $index;
                                    @endphp
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                                {{ $planNumber }}
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Content Plan #{{ $planNumber }}</h4>
                                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                    <span><i class="fas fa-calendar-alt mr-1"></i>{{ $contentPlan->created_at->format('d M Y') }}</span>
                                                    <span><i class="fas fa-file-alt mr-1"></i>{{ $contentPlan->content_count }} konten</span>
                                                    <span><i class="fas fa-clock mr-1"></i>{{ $contentPlan->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('front.content.detail', $contentPlan->content_plan_id) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition-all duration-300 text-sm">
                                            <i class="fas fa-eye mr-2"></i>
                                            Detail
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            @else
                <!-- Empty State - No Analysis Yet -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-6">
                            <i class="fas fa-rocket text-white text-3xl"></i>
                        </div>

                        <h3 class="text-2xl font-semibold text-gray-800 mb-3">Mulai Journey Bisnis Anda</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Belum ada analisa bisnis? Ayo mulai dengan analisa mendalam untuk memahami potensi dan strategi bisnis Anda!
                        </p>

                        <a href="{{ route('front.form') }}"
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-play mr-3"></i>
                            Mulai Analisa Bisnis
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
