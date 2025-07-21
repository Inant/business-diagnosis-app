@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-4">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Hasil Analisa Bisnis</h1>
                <p class="text-gray-600">Insight dan rekomendasi berdasarkan data yang Anda berikan</p>
            </div>

            @if($session->gemini_response)
                <!-- Status Success -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6">
                        <div class="flex items-center text-white">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold">Analisa Berhasil Diselesaikan</h2>
                                <p class="text-green-100">Berikut adalah hasil analisa komprehensif untuk bisnis Anda</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis Content -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 md:p-12">
                        <!-- Analysis Header -->
                        <div class="flex items-center mb-8 pb-6 border-b border-gray-200">
                            <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-analytics text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Laporan Analisa</h3>
                                <p class="text-gray-500">
                                    Dibuat pada {{ \Carbon\Carbon::parse($session->created_at)->format('d F Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="ml-auto">
                                <button onclick="printReport()" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-300 mr-2">
                                    <i class="fas fa-print mr-2"></i>Print
                                </button>
                                <button onclick="downloadPDF()" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-300">
                                    <i class="fas fa-download mr-2"></i>Download PDF
                                </button>
                            </div>
                        </div>

                        <!-- Formatted Analysis Content -->
                        <div id="analysis-content" class="prose prose-lg max-w-none">
                            {!! formatAnalysisContent($session->gemini_response) !!}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-50 px-8 py-6 md:px-12">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="text-sm">Analisa ini dibuat berdasarkan informasi yang Anda berikan</span>
                            </div>
                            <div class="flex space-x-4">
                                <a href="{{ route('front.form') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                                    <i class="fas fa-plus mr-2"></i>Analisa Baru
                                </a>
                                <button onclick="shareAnalysis()" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300">
                                    <i class="fas fa-share-alt mr-2"></i>Analisa SWOT
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Resources -->
                <div class="mt-8 grid md:grid-cols-2 gap-6">
                    <!-- Tips Card -->
{{--                    <div class="bg-white rounded-xl shadow-lg p-6">--}}
{{--                        <div class="flex items-start">--}}
{{--                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4 mt-1">--}}
{{--                                <i class="fas fa-lightbulb text-yellow-600"></i>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <h4 class="font-semibold text-gray-800 mb-3">Tips Implementasi</h4>--}}
{{--                                <ul class="text-gray-600 text-sm space-y-2">--}}
{{--                                    <li class="flex items-start">--}}
{{--                                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>--}}
{{--                                        <span>Prioritaskan rekomendasi berdasarkan dampak dan kemudahan implementasi</span>--}}
{{--                                    </li>--}}
{{--                                    <li class="flex items-start">--}}
{{--                                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>--}}
{{--                                        <span>Buat timeline yang realistis untuk setiap action item</span>--}}
{{--                                    </li>--}}
{{--                                    <li class="flex items-start">--}}
{{--                                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>--}}
{{--                                        <span>Monitor progress secara berkala dan adjust strategi jika diperlukan</span>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <!-- Feedback Card -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-comments text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-3">Feedback Analisa</h4>
                                <p class="text-gray-600 text-sm mb-4">Bagaimana menurut Anda hasil analisa ini?</p>
                                <div class="flex space-x-2">
                                    <button onclick="giveFeedback('helpful')" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-300 text-sm">
                                        <i class="fas fa-thumbs-up mr-1"></i>Sangat Membantu
                                    </button>
                                    <button onclick="giveFeedback('improve')" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors duration-300 text-sm">
                                        <i class="fas fa-edit mr-1"></i>Perlu Diperbaiki
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Processing State -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-8 md:p-12 text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <div class="animate-spin">
                                <i class="fas fa-cog text-white text-2xl"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Analisa Sedang Diproses</h2>
                        <p class="text-gray-600 mb-8">Sistem kami sedang menganalisa data yang Anda berikan. Proses ini membutuhkan waktu beberapa menit.</p>

                        <!-- Progress Animation -->
                        <div class="max-w-md mx-auto mb-8">
                            <div class="flex justify-between text-sm text-gray-500 mb-2">
                                <span>Progress</span>
                                <span id="progress-text">Memproses data...</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="processing-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"></div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg text-left max-w-2xl mx-auto mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-2">Yang Sedang Dilakukan:</h4>
                                    <ul class="text-blue-700 text-sm space-y-1">
                                        <li id="step1" class="opacity-50">• Menganalisa data bisnis Anda</li>
                                        <li id="step2" class="opacity-50">• Mengidentifikasi peluang dan tantangan</li>
                                        <li id="step3" class="opacity-50">• Menyusun rekomendasi strategis</li>
                                        <li id="step4" class="opacity-50">• Memformat laporan final</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <button onclick="refreshPage()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh Halaman
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300">
                                <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Auto refresh script -->
                <script>
                    // Auto refresh every 30 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 30000);

                    // Simulate processing progress
                    let progress = 0;
                    const progressBar = document.getElementById('processing-bar');
                    const progressText = document.getElementById('progress-text');
                    const steps = ['step1', 'step2', 'step3', 'step4'];

                    const progressInterval = setInterval(() => {
                        progress += Math.random() * 15;
                        if (progress > 95) progress = 95;

                        progressBar.style.width = progress + '%';

                        // Update steps
                        if (progress > 25) {
                            document.getElementById('step1').classList.remove('opacity-50');
                            progressText.textContent = 'Menganalisa data...';
                        }
                        if (progress > 50) {
                            document.getElementById('step2').classList.remove('opacity-50');
                            progressText.textContent = 'Mengidentifikasi peluang...';
                        }
                        if (progress > 75) {
                            document.getElementById('step3').classList.remove('opacity-50');
                            progressText.textContent = 'Menyusun rekomendasi...';
                        }
                        if (progress > 90) {
                            document.getElementById('step4').classList.remove('opacity-50');
                            progressText.textContent = 'Hampir selesai...';
                        }
                    }, 2000);
                </script>
            @endif
        </div>
    </div>

    <style>
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1f2937;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .prose h1 { font-size: 2rem; }
        .prose h2 { font-size: 1.5rem; }
        .prose h3 { font-size: 1.25rem; }

        .prose p {
            margin-bottom: 1.5rem;
            line-height: 1.75;
            color: #374151;
        }

        .prose ul, .prose ol {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }

        .prose li {
            margin: 0.5rem 0;
            color: #374151;
        }

        .prose strong {
            color: #1f2937;
            font-weight: 600;
        }

        .prose blockquote {
            border-left: 4px solid #3b82f6;
            background: #eff6ff;
            padding: 1rem 1.5rem;
            margin: 2rem 0;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .prose code {
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: #1f2937;
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        .prose th, .prose td {
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            text-align: left;
        }

        .prose th {
            background: #f9fafb;
            font-weight: 600;
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
        function refreshPage() {
            location.reload();
        }

        function printReport() {
            window.print();
        }

        function downloadPDF() {
            // You can implement PDF generation here
            alert('Fitur download PDF akan segera tersedia');
        }

        function shareAnalysis() {
            if (navigator.share) {
                navigator.share({
                    title: 'Hasil Analisa Bisnis',
                    text: 'Lihat hasil analisa bisnis saya',
                    url: window.location.href
                });
            } else {
                // Fallback to copy URL
                navigator.clipboard.writeText(window.location.href);
                alert('Link berhasil disalin ke clipboard');
            }
        }

        function giveFeedback(type) {
            // You can implement feedback submission here
            const message = type === 'helpful' ? 'Terima kasih atas feedback positif Anda!' : 'Terima kasih, kami akan terus memperbaiki kualitas analisa.';
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
