@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-0">
                        Detail Sesi Analisa User
                    </h1>
                    <a href="{{ route('backoffice.sessions') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke List
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 01-4-4v-2m0 0V7a4 4 0 114 0v6m-7 4a1 1 0 100-2 1 1 0 000 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Tanggal & Waktu</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $session->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">User</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $session->user->name ?? 'User tidak diketahui' }}</p>
                            @if($session->user->email)
                                <p class="text-sm text-gray-600">{{ $session->user->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Answers Section -->
            <div class="bg-white rounded-lg shadow-sm border mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Jawaban User
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                           {{ $session->userAnswers->count() }} pertanyaan
                       </span>
                    </h2>
                </div>

                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($session->userAnswers as $index => $answer)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-base font-semibold text-gray-900 mb-2">
                                            {{ $answer->question->title ?? 'Pertanyaan tidak ditemukan' }}
                                        </h3>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $answer->answer }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- AI Responses Section -->
            @foreach($session->aiResponses as $index => $ai)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r {{ $ai->step === 'diagnosis' ? 'from-blue-500 to-blue-600' : ($ai->step === 'swot' ? 'from-green-500 to-green-600' : 'from-purple-500 to-purple-500') }} px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            @if($ai->step === 'diagnosis')
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($ai->step === 'swot')
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            @endif
                            {{ ucwords(str_replace('_', ' ', $ai->step)) }} Result
                            <span class="ml-3 px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                               @if($ai->step === 'diagnosis')
                                    Analisa Awal
                                @elseif($ai->step === 'swot')
                                    Analisa SWOT
                                @else
                                    Rencana Konten
                                @endif
                           </span>
                        </h3>
                    </div>

                    <div class="p-6">
                        @if($ai->step === 'content_plan')
                            <!-- Content Planning Cards -->
                            @php
                                $contentData = json_decode($ai->ai_response, true);
                            @endphp

                            @if($contentData && is_array($contentData))
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($contentData as $content)
                                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                            <!-- Card Header -->
                                            <div class="bg-gradient-to-r {{ $content['Pilar_Konten'] === 'Edukasi' ? 'from-blue-500 to-blue-600' : ($content['Pilar_Konten'] === 'Inspirasi' ? 'from-pink-500 to-pink-600' : 'from-purple-500 to-purple-600') }} px-6 py-4">
                                                <div class="flex items-center justify-between text-white">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold">
                                                            {{ $content['Hari_ke'] }}
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm opacity-90">Hari ke-{{ $content['Hari_ke'] }}</p>
                                                            <p class="text-xs opacity-75">{{ $content['Pilar_Konten'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Content -->
                                            <div class="p-6">
                                                <!-- Title -->
                                                <h3 class="text-lg font-bold text-gray-900 mb-4 leading-tight">
                                                    {{ $content['Judul_Konten'] }}
                                                </h3>

                                                <!-- Format Badge -->
                                                <div class="mb-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $content['Pilar_Konten'] === 'Edukasi' ? 'bg-blue-100 text-blue-800' : ($content['Pilar_Konten'] === 'Inspirasi' ? 'bg-pink-100 text-pink-800' : 'bg-purple-100 text-purple-800') }}">
                                                        {{ $content['Rekomendasi_Format'] }}
                                                    </span>
                                                </div>

                                                <!-- Hook -->
                                                <div class="mb-4">
                                                    <p class="text-sm font-medium text-gray-500 mb-2">HOOK PEMBUKA:</p>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-sm text-gray-700 italic">"{{ $content['Hook'] }}"</p>
                                                    </div>
                                                </div>

                                                <!-- Main Points -->
                                                <div class="mb-4">
                                                    <p class="text-sm font-medium text-gray-500 mb-2">Poin-Poin Utama:</p>
                                                    <ul class="space-y-2">
                                                        @foreach($content['Script_Poin_Utama'] as $point)
                                                            <li class="flex items-start">
                                                                <div class="w-2 h-2 {{ $content['Pilar_Konten'] === 'Edukasi' ? 'bg-blue-500' : ($content['Pilar_Konten'] === 'Inspirasi' ? 'bg-pink-500' : 'bg-purple-500') }} rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                                                <p class="text-sm text-gray-600 leading-relaxed">{{ $point }}</p>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <!-- CTA -->
                                                <div class="mt-6 pt-4 border-t border-gray-100">
                                                    <p class="text-sm font-medium text-gray-500 mb-2">CALL TO ACTION</p>
                                                    <div class="bg-gradient-to-r {{ $content['Pilar_Konten'] === 'Edukasi' ? 'from-blue-50 to-blue-100' : ($content['Pilar_Konten'] === 'Inspirasi' ? 'from-pink-50 to-pink-100' : 'from-purple-50 to-purple-100') }} rounded-lg p-3">
                                                        <p class="text-sm {{ $content['Pilar_Konten'] === 'Edukasi' ? 'text-blue-800' : ($content['Pilar_Konten'] === 'Inspirasi' ? 'text-pink-800' : 'text-purple-800') }} font-medium">
                                                            {{ $content['Call_to_Action_(CTA)'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Fallback jika JSON tidak valid -->
                                <div class="mb-6">
                                    <div class="flex items-center mb-4">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Hasil Analisa</h4>
                                    </div>
                                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6 shadow-inner">
                                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                            {!! formatAnalysisContent($ai->ai_response) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Text Response untuk step lainnya -->
                            <div class="mb-6">
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Hasil Analisa</h4>
                                </div>
                                <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6 shadow-inner">
                                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                        {!! formatAnalysisContent($ai->ai_response) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Footer Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-between sm:items-center mt-8 p-6 bg-white rounded-lg shadow-sm border">
                <div class="text-sm text-gray-500">
                    <p>Sesi ini memiliki <span class="font-semibold">{{ $session->userAnswers->count() }}</span> jawaban dan <span class="font-semibold">{{ $session->aiResponses->count() }}</span> hasil analisa</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Hasil
                    </button>
                    <a href="{{ route('backoffice.sessions') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleJson(elementId) {
            const element = document.getElementById(elementId);
            if (element.style.display === 'none') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;

            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                button.classList.add('text-green-400');

                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.classList.remove('text-green-400');
                }, 2000);
            });
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-gray-50 {
                background: white !important;
            }
        }

        /* Custom scrollbar for JSON area */
        pre::-webkit-scrollbar {
            height: 8px;
        }

        pre::-webkit-scrollbar-track {
            background: #374151;
            border-radius: 4px;
        }

        pre::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 4px;
        }

        pre::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
    </style>
@endsection
