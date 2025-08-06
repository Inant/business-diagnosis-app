@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-chart-bar text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Analisa SWOT & Rencana Marketing</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Strategi bisnis komprehensif berdasarkan analisa mendalam</p>
            </div>

            <!-- Status Success -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-4 sm:p-6">
                    <div class="flex items-center text-white">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-check text-lg sm:text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold">Analisa SWOT Berhasil Diselesaikan</h2>
                            <p class="text-sm sm:text-base text-purple-100">Berikut adalah strategi marketing dan rencana bisnis lengkap untuk Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SWOT Analysis Content -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 sm:p-8 md:p-12">
                    <!-- Analysis Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                                <i class="fas fa-chart-bar text-white text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Laporan SWOT & Marketing</h3>
                                <p class="text-sm sm:text-base text-gray-500">
                                    Dibuat pada {{ \Carbon\Carbon::parse($swot->created_at)->format('d F Y, H:i') }} WIB
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-2 sm:ml-auto">
                            <button onclick="printReport()" class="flex-1 sm:flex-none px-3 py-2 sm:px-4 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors duration-300 text-sm">
                                <i class="fas fa-print mr-1 sm:mr-2"></i><span class="hidden xs:inline">Print</span>
                            </button>
{{--                            <button onclick="downloadPDF()" class="flex-1 sm:flex-none px-3 py-2 sm:px-4 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 transition-colors duration-300 text-sm">--}}
{{--                                <i class="fas fa-download mr-1 sm:mr-2"></i><span class="hidden xs:inline">PDF</span>--}}
{{--                            </button>--}}
                        </div>
                    </div>

                    <!-- SWOT Content -->
                    <div id="swot-content" class="prose prose-sm sm:prose-lg max-w-none">
                        {!! formatAnalysisContent($swot->ai_response) !!}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:py-6 md:px-12">
                    <div class="flex flex-col space-y-4 lg:flex-row lg:justify-between lg:items-center lg:space-y-0">
                        <div class="flex items-center text-gray-600 text-sm sm:text-base order-2 lg:order-1">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Analisa SWOT ini dibuat berdasarkan data bisnis sebelumnya</span>
                        </div>
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 order-1 lg:order-2">
                            <a href="{{ route('front.result', $session->id) }}" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg font-semibold hover:from-gray-600 hover:to-gray-700 transition-all duration-300 text-center text-sm sm:text-base">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Hasil Awal
                            </a>
                            <a href="{{ route('front.content.create') }}"
                               class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 text-center text-sm sm:text-base">
                                <i class="fas fa-magic mr-2"></i>Buat Konten
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Resources -->
            <div class="mt-6 sm:mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Implementation Tips Card -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1">
                            <i class="fas fa-lightbulb text-yellow-600 text-sm sm:text-base"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">Tips Implementasi</h4>
                            <ul class="text-gray-600 text-xs sm:text-sm space-y-1 sm:space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Prioritaskan rekomendasi berdasarkan dampak dan kemudahan implementasi</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Buat timeline yang realistis untuk setiap action item</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                    <span>Monitor progress secara berkala dan adjust strategi jika diperlukan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Next Steps Card -->
{{--                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">--}}
{{--                    <div class="flex items-start">--}}
{{--                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1">--}}
{{--                            <i class="fas fa-rocket text-blue-600 text-sm sm:text-base"></i>--}}
{{--                        </div>--}}
{{--                        <div class="flex-1">--}}
{{--                            <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">Langkah Selanjutnya</h4>--}}
{{--                            <p class="text-gray-600 text-xs sm:text-sm mb-3 sm:mb-4">Siap untuk mengimplementasikan strategi SWOT Anda?</p>--}}
{{--                            <div class="flex flex-col xs:flex-row space-y-2 xs:space-y-0 xs:space-x-2">--}}
{{--                                <a href="{{ route('front.content.form', $session->id) }}"--}}
{{--                                   class="px-3 sm:px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                                    <i class="fas fa-magic mr-1"></i>Buat Konten Marketing--}}
{{--                                </a>--}}

{{--                                <button onclick="giveFeedback('swot')" class="px-3 sm:px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                                    <i class="fas fa-star mr-1"></i>Berikan Rating--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>

            <!-- Navigation Links -->
{{--            <div class="mt-6 sm:mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">--}}
{{--                <div class="text-center">--}}
{{--                    <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 text-sm sm:text-base">Navigasi Cepat</h4>--}}
{{--                    <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">--}}
{{--                        <a href="{{ route('front.form') }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                            <i class="fas fa-plus mr-2"></i>Analisa Bisnis Baru--}}
{{--                        </a>--}}
{{--                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                            <i class="fas fa-home mr-2"></i>Dashboard--}}
{{--                        </a>--}}
{{--                        <button onclick="shareAnalysis()" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors duration-300 text-xs sm:text-sm">--}}
{{--                            <i class="fas fa-share-alt mr-2"></i>Bagikan Hasil--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    <style>
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1f2937;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .prose h1 { font-size: 1.5rem; }
        .prose h2 { font-size: 1.25rem; }
        .prose h3 { font-size: 1.125rem; }

        @media (min-width: 640px) {
            .prose h1 { font-size: 2rem; }
            .prose h2 { font-size: 1.5rem; }
            .prose h3 { font-size: 1.25rem; }
        }

        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
            color: #374151;
            font-size: 0.875rem;
        }

        @media (min-width: 640px) {
            .prose p {
                margin-bottom: 1.5rem;
                font-size: 1rem;
            }
        }

        .prose ul, .prose ol {
            margin: 1rem 0;
            padding-left: 1.25rem;
        }

        @media (min-width: 640px) {
            .prose ul, .prose ol {
                margin: 1.5rem 0;
                padding-left: 1.5rem;
            }
        }

        .prose li {
            margin: 0.25rem 0;
            color: #374151;
            font-size: 0.875rem;
        }

        @media (min-width: 640px) {
            .prose li {
                margin: 0.5rem 0;
                font-size: 1rem;
            }
        }

        .prose strong {
            color: #1f2937;
            font-weight: 600;
        }

        .prose blockquote {
            border-left: 4px solid #8b5cf6;
            background: #f3e8ff;
            padding: 0.75rem 1rem;
            margin: 1.5rem 0;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        @media (min-width: 640px) {
            .prose blockquote {
                padding: 1rem 1.5rem;
                margin: 2rem 0;
            }
        }

        .prose code {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            color: #1f2937;
        }

        @media (min-width: 640px) {
            .prose code {
                font-size: 0.875rem;
            }
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.875rem;
        }

        @media (min-width: 640px) {
            .prose table {
                margin: 2rem 0;
                font-size: 1rem;
            }
        }

        .prose th, .prose td {
            border: 1px solid #d1d5db;
            padding: 0.5rem;
            text-align: left;
        }

        @media (min-width: 640px) {
            .prose th, .prose td {
                padding: 0.75rem;
            }
        }

        .prose th {
            background: #f9fafb;
            font-weight: 600;
        }

        /* Responsive breakpoint for extra small screens */
        @media (min-width: 475px) {
            .xs\:inline { display: inline !important; }
            .xs\:flex-row { flex-direction: row !important; }
            .xs\:space-y-0 > :not([hidden]) ~ :not([hidden]) { margin-top: 0 !important; }
            .xs\:space-x-2 > :not([hidden]) ~ :not([hidden]) { margin-left: 0.5rem !important; }
        }

        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-gradient-to-br { background: white !important; }
            .shadow-xl { box-shadow: none !important; }
        }
    </style>

    <script>
        function printReport() {
            window.print();
        }

        function downloadPDF() {
            alert('Fitur download PDF akan segera tersedia');
        }

        function createContent() {
            // Action akan diupdate nanti
            alert('Fitur Buat Konten akan segera tersedia');
        }

        function shareAnalysis() {
            if (navigator.share) {
                navigator.share({
                    title: 'Hasil Analisa SWOT & Marketing',
                    text: 'Lihat hasil analisa SWOT dan rencana marketing saya',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link berhasil disalin ke clipboard');
            }
        }

        function giveFeedback(type) {
            const message = 'Terima kasih atas feedback Anda untuk analisa SWOT ini!';
            alert(message);
        }

        // Smooth scrolling for internal links
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endsection
