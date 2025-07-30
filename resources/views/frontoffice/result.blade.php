@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-chart-line text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Hasil Analisa Bisnis</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Insight dan rekomendasi berdasarkan data yang Anda berikan</p>
            </div>

            @if($diagnosis && $diagnosis->ai_response)
                <!-- Status Success -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4 sm:p-6">
                        <div class="flex items-center text-white">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 sm:mr-4">
                                <i class="fas fa-check text-lg sm:text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">Analisa Berhasil Diselesaikan</h2>
                                <p class="text-sm sm:text-base text-green-100">Berikut adalah hasil analisa komprehensif untuk bisnis Anda</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analysis Content -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-4 sm:p-8 md:p-12">
                        <!-- Analysis Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                                    <i class="fas fa-analytics text-white text-lg sm:text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800">Laporan Analisa</h3>
                                    <p class="text-sm sm:text-base text-gray-500">
                                        Dibuat pada {{ \Carbon\Carbon::parse($diagnosis->created_at)->format('d F Y, H:i') }} WIB
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-2 sm:ml-auto">
                                <button onclick="printReport()" class="flex-1 sm:flex-none px-3 py-2 sm:px-4 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-300 text-sm">
                                    <i class="fas fa-print mr-1 sm:mr-2"></i><span class="hidden xs:inline">Print</span>
                                </button>
{{--                                <button onclick="downloadPDF()" class="flex-1 sm:flex-none px-3 py-2 sm:px-4 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-300 text-sm">--}}
{{--                                    <i class="fas fa-download mr-1 sm:mr-2"></i><span class="hidden xs:inline">PDF</span>--}}
{{--                                </button>--}}
                            </div>
                        </div>

                        <!-- Formatted Analysis Content -->
                        <div id="analysis-content" class="prose prose-sm sm:prose-lg max-w-none">
                            {!! formatAnalysisContent($diagnosis->ai_response) !!}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-50 px-4 py-4 sm:px-8 sm:py-6 md:px-12">
                        <div class="flex flex-col space-y-4 lg:flex-row lg:justify-between lg:items-center lg:space-y-0">
                            <div class="flex items-center text-gray-600 text-sm sm:text-base order-2 lg:order-1">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Analisa ini dibuat berdasarkan informasi yang Anda berikan</span>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 order-1 lg:order-2">
{{--                                <a href="{{ route('front.form') }}" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 text-center text-sm sm:text-base">--}}
{{--                                    <i class="fas fa-plus mr-2"></i>Analisa Baru--}}
{{--                                </a>--}}
                                <a href="#" onclick="startFurtherAnalysis('{{ route('front.swot.form', $session->id) }}')" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300 text-center text-sm sm:text-base">
                                    <i class="fas fa-chart-bar mr-2"></i>Analisa Lebih Lanjut?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Resources -->
                <div class="mt-6 sm:mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Feedback Card -->
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1">
                                <i class="fas fa-comments text-blue-600 text-sm sm:text-base"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">Feedback Analisa</h4>
                                <p class="text-gray-600 text-xs sm:text-sm mb-3 sm:mb-4">Bagaimana menurut Anda hasil analisa ini?</p>
                                <div class="flex flex-col xs:flex-row space-y-2 xs:space-y-0 xs:space-x-2">
                                    <button onclick="giveFeedback('helpful')" class="px-3 sm:px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-300 text-xs sm:text-sm">
                                        <i class="fas fa-thumbs-up mr-1"></i>Sangat Membantu
                                    </button>
                                    <button onclick="giveFeedback('improve')" class="px-3 sm:px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors duration-300 text-xs sm:text-sm">
                                        <i class="fas fa-edit mr-1"></i>Perlu Diperbaiki
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Processing State -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 sm:p-8 md:p-12 text-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                            <div class="animate-spin">
                                <i class="fas fa-cog text-white text-xl sm:text-2xl"></i>
                            </div>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">Analisa Sedang Diproses</h2>
                        <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mb-8 px-4">Sistem kami sedang menganalisa data yang Anda berikan. Proses ini membutuhkan waktu beberapa menit.</p>

                        <!-- Progress Animation -->
                        <div class="max-w-md mx-auto mb-6 sm:mb-8">
                            <div class="flex justify-between text-xs sm:text-sm text-gray-500 mb-2">
                                <span>Progress</span>
                                <span id="progress-text">Memproses data...</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="processing-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"></div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-r-lg text-left max-w-2xl mx-auto mb-4 sm:mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mr-2 sm:mr-3 mt-1"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-800 mb-2 text-sm sm:text-base">Yang Sedang Dilakukan:</h4>
                                    <ul class="text-blue-700 text-xs sm:text-sm space-y-1">
                                        <li id="step1" class="opacity-50">• Menganalisa data bisnis Anda</li>
                                        <li id="step2" class="opacity-50">• Mengidentifikasi peluang dan tantangan</li>
                                        <li id="step3" class="opacity-50">• Menyusun rekomendasi strategis</li>
                                        <li id="step4" class="opacity-50">• Memformat laporan final</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <button onclick="refreshPage()" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 text-sm sm:text-base">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh Halaman
                            </button>
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 text-center text-sm sm:text-base">
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

    <!-- Loading Modal for Further Analysis -->
    <div id="further-analysis-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
            <div class="mb-6">
                <div class="w-16 h-16 mx-auto mb-4">
                    <div class="loading-spinner"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Mempersiapkan Analisa</h3>
                <p class="text-gray-600 text-sm">Mohon tunggu, kami sedang menyiapkan analisa lebih lanjut...</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="further-loading-progress bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full"></div>
            </div>
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
            border-left: 4px solid #3b82f6;
            background: #eff6ff;
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

        /* Loading Modal for Further Analysis */
        #further-analysis-modal {
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        #further-analysis-modal.show {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #a855f7;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            position: relative;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Further Analysis Progress Bar */
        .further-loading-progress {
            width: 0%;
            transition: width 3s ease-in-out;
        }

        /* Modal Animation */
        #further-analysis-modal.show .bg-white {
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

        /* Pulse effect untuk spinner */
        .loading-spinner::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            width: 72px;
            height: 72px;
            border: 2px solid rgba(168, 85, 247, 0.2);
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

        function startFurtherAnalysis(url) {
            // Show loading modal
            showFurtherAnalysisModal();

            // Simulate some processing time before redirect
            setTimeout(() => {
                window.location.href = url;
            }, 2000); // 2 seconds delay
        }

        function showFurtherAnalysisModal() {
            const modal = document.getElementById('further-analysis-modal');
            modal.classList.remove('hidden');

            // Force reflow untuk memastikan perubahan class diterapkan
            modal.offsetHeight;

            modal.classList.add('show');

            // Prevent scrolling while modal is open
            document.body.style.overflow = 'hidden';

            // Trigger progress bar animation
            setTimeout(() => {
                const progressBar = modal.querySelector('.further-loading-progress');
                progressBar.style.width = '95%';
            }, 100);

            // Add click prevention
            modal.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            });
        }

        function hideFurtherAnalysisModal() {
            const modal = document.getElementById('further-analysis-modal');
            modal.classList.remove('show');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
