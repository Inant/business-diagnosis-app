@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mb-4">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Kalender Konten Bisnis Anda</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Strategi konten yang dipersonalisasi berdasarkan analisa bisnis Anda. Siap untuk meningkatkan engagement!</p>
            </div>

            @php
                $pilarCounts = $contentIdeas->groupBy('pilar_konten')->map->count();
                $formatCounts = $contentIdeas->groupBy('rekomendasi_format')->map->count();
            @endphp

                <!-- Statistics Overview -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Ringkasan Kalender Konten
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600">{{ $contentIdeas->count() }}</div>
                        <div class="text-sm text-gray-600">Total Konten</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600">{{ $pilarCounts['Edukasi'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Konten Edukasi</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl">
                        <div class="text-2xl font-bold text-pink-600">{{ $pilarCounts['Inspirasi'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Konten Inspirasi</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">{{ $pilarCounts['Interaksi'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Konten Interaksi</div>
                    </div>
                </div>
            </div>

            <!-- Content Calendar Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach($contentIdeas as $item)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 h-full flex flex-col">
                        <!-- Card Header -->
                        <div class="relative p-6 pb-4
                        @if($item->pilar_konten === 'Edukasi') bg-gradient-to-r from-blue-500 to-indigo-600
                        @elseif($item->pilar_konten === 'Inspirasi') bg-gradient-to-r from-pink-500 to-rose-600
                        @else bg-gradient-to-r from-green-500 to-emerald-600 @endif">

                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center text-white">
                                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                        <span class="font-bold text-lg">{{ $item->hari_ke }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold opacity-90">Hari ke-{{ $item->hari_ke }}</div>
                                        <div class="text-xs opacity-75">{{ $item->pilar_konten }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($item->pilar_konten === 'Edukasi')
                                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                                    @elseif($item->pilar_konten === 'Inspirasi')
                                        <i class="fas fa-lightbulb text-white text-xl"></i>
                                    @else
                                        <i class="fas fa-bullhorn text-white text-xl"></i>
                                    @endif
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-white leading-tight">{{ $item->judul_konten }}</h3>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col flex-grow">
                            <!-- Format Badge -->
                            <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->pilar_konten === 'Edukasi') bg-blue-100 text-blue-800
                                @elseif($item->pilar_konten === 'Inspirasi') bg-pink-100 text-pink-800
                                @else bg-green-100 text-green-800 @endif">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $item->rekomendasi_format ?? 'Post Biasa' }}
                            </span>
                            </div>

                            <!-- Hook -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-xl border-l-4
                            @if($item->pilar_konten === 'Edukasi') border-blue-400
                            @elseif($item->pilar_konten === 'Inspirasi') border-pink-400
                            @else border-green-400 @endif">
                                <div class="text-xs font-semibold text-gray-500 mb-1">HOOK PEMBUKA:</div>
                                <div class="text-sm italic
                                @if($item->pilar_konten === 'Edukasi') text-blue-700
                                @elseif($item->pilar_konten === 'Inspirasi') text-pink-700
                                @else text-green-700 @endif font-medium">
                                    "{{ $item->hook }}"
                                </div>
                            </div>

                            <!-- Content Points -->
                            <div class="mb-4 flex-grow">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-list-ul mr-2"></i>
                                    Poin-Poin Utama:
                                </h4>
                                <ul class="space-y-2">
                                    @php
                                        $poinUtama = json_decode($item->script_poin_utama, true) ?? [];
                                    @endphp
                                    @foreach($poinUtama as $poin)
                                        <li class="flex items-start text-sm text-gray-600">
                                            <div class="w-2 h-2 rounded-full mt-2 mr-3 flex-shrink-0
                                            @if($item->pilar_konten === 'Edukasi') bg-blue-400
                                            @elseif($item->pilar_konten === 'Inspirasi') bg-pink-400
                                            @else bg-green-400 @endif"></div>
                                            <span>{{ $poin }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Generate Shooting Script Button -->
                            <div class="mb-4">
                                <a href="{{ route('front.shooting.form', $item->id) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg font-semibold text-sm hover:from-orange-600 hover:to-red-700 transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-video mr-2"></i>
                                    Generate Shooting Script
                                </a>
                            </div>
                        </div>

                        <!-- CTA Section - HIGHLIGHTED -->
                        <div class="p-0 mt-auto">
                            <div class="relative overflow-hidden
                            @if($item->pilar_konten === 'Edukasi') bg-gradient-to-r from-blue-600 to-blue-700
                            @elseif($item->pilar_konten === 'Inspirasi') bg-gradient-to-r from-pink-600 to-pink-700
                            @else bg-gradient-to-r from-green-600 to-green-700 @endif">

                                <!-- Animated background -->
                                <div class="absolute inset-0 bg-white bg-opacity-10 transform -skew-y-1"></div>

                                <div class="relative p-6">
                                    <!-- CTA Label with Icon -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center text-white">
                                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2">
                                                <i class="fas fa-bullseye text-sm"></i>
                                            </div>
                                            <span class="text-xs font-bold uppercase tracking-widest">Call to Action</span>
                                        </div>
                                        <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center animate-pulse">
                                            <i class="fas fa-exclamation text-white text-xs"></i>
                                        </div>
                                    </div>

                                    <!-- CTA Content -->
                                    <div class="bg-white bg-opacity-15 rounded-lg p-4 backdrop-blur-sm">
                                        <div class="text-white">
                                            <div class="flex items-start">
                                                <i class="fas fa-quote-left text-white text-opacity-60 mr-2 mt-1 flex-shrink-0"></i>
                                                <div>
                                                    <p class="font-bold text-base leading-tight mb-2">{{ $item->call_to_action }}</p>
                                                </div>
                                                <i class="fas fa-quote-right text-white text-opacity-60 ml-2 mt-1 flex-shrink-0"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 border-t">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Kalender Konten Siap Digunakan!</h3>
                        <p class="text-gray-600 text-sm">Mulai implementasikan strategi konten Anda hari ini</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                        <button onclick="downloadCalendar()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </button>
                        <button onclick="shareCalendar()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                            <i class="fas fa-share-alt mr-2"></i>Bagikan
                        </button>
                        <a href="{{ route('front.content.form', $session->id) }}"
                           class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-semibold hover:from-purple-600 hover:to-pink-700 transition-all duration-300 text-center">
                            <i class="fas fa-sync-alt mr-2"></i>Generate Ulang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="mt-8 grid md:grid-cols-2 gap-6">
                <!-- Implementation Tips -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-lightbulb text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Tips Implementasi</h4>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Siapkan visual yang menarik untuk setiap konten</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Posting pada waktu optimal untuk audience Anda</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Monitor engagement dan adjust strategi sesuai respons</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Best Practices -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-star text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Best Practices</h4>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Konsisten dengan brand voice dan visual identity</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Engage aktif dengan komentar dan feedback audience</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Analisa performa dan optimasi konten berkelanjutan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Card hover animations */
        .transform {
            transition: transform 0.3s ease-in-out;
        }

        .hover\:scale-105:hover {
            transform: scale(1.02);
        }

        /* CTA Section Enhanced Styling */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }

        /* Skewed background effect */
        .transform.-skew-y-1 {
            transform: skewY(-1deg);
        }

        /* Copy CTA button animation */
        button:hover .fas.fa-copy {
            animation: bounce 0.5s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 60%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-3px);
            }
            80% {
                transform: translateY(-1px);
            }
        }

        /* Custom scrollbar for debug section */
        pre::-webkit-scrollbar {
            height: 6px;
        }

        pre::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        pre::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        pre::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .text-4xl {
                font-size: 2.5rem;
            }

            .text-xl {
                font-size: 1.1rem;
            }

            .p-6 {
                padding: 1.25rem;
            }

            .md\:p-8 {
                padding: 1.25rem;
            }
        }

        @media (max-width: 640px) {
            .text-4xl {
                font-size: 2rem;
            }

            .grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
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
        function downloadCalendar() {
            // Implementation for PDF download
            alert('Fitur download PDF akan segera tersedia');
        }

        function shareCalendar() {
            if (navigator.share) {
                navigator.share({
                    title: 'Kalender Konten Bisnis',
                    text: 'Lihat kalender konten bisnis yang telah saya buat',
                    url: window.location.href
                });
            } else {
                // Fallback to copy URL
                navigator.clipboard.writeText(window.location.href);

                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Link Tersalin!';
                button.classList.add('bg-green-500');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500');
                }, 2000);
            }
        }

        // Copy CTA functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.transform');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Add copy CTA functionality
            document.querySelectorAll('button').forEach(button => {
                if (button.textContent.includes('Copy CTA')) {
                    button.addEventListener('click', function() {
                        const card = this.closest('.bg-white');
                        const ctaText = card.querySelector('p.font-bold').textContent;

                        navigator.clipboard.writeText(ctaText).then(() => {
                            const originalText = this.innerHTML;
                            this.innerHTML = '<i class="fas fa-check mr-2"></i>Tersalin!';
                            this.classList.add('bg-green-500', 'text-white');

                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.classList.remove('bg-green-500', 'text-white');
                            }, 2000);
                        });
                    });
                }
            });
        });
    </script>
@endsection
