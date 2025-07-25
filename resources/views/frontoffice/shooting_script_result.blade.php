@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-100 py-8">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full mb-4">
                    <i class="fas fa-video text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Shooting Script Siap Pakai</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Script shooting yang detail dan terstruktur untuk konten video Anda</p>
            </div>

            <!-- Content Overview -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r
                            @if($contentIdea->pilar_konten === 'Edukasi') from-blue-500 to-indigo-600
                            @elseif($contentIdea->pilar_konten === 'Inspirasi') from-pink-500 to-rose-600
                            @else from-green-500 to-emerald-600 @endif
                            rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">{{ $contentIdea->hari_ke }}</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $contentIdea->judul_konten }}</h2>
                                <p class="text-sm text-gray-600">{{ $contentIdea->pilar_konten }} - Hari ke-{{ $contentIdea->hari_ke }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Hook Original:</p>
                            <p class="text-sm text-gray-600 italic">"{{ $contentIdea->hook }}"</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700">Gaya Pembawaan:</span>
                            <span class="text-sm text-orange-700 font-bold">{{ $shootingScript->gaya_pembawaan }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700">Target Durasi:</span>
                            <span class="text-sm text-blue-700 font-bold">{{ $shootingScript->target_durasi }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700">Penyebutan Audiens:</span>
                            <span class="text-sm text-green-700 font-bold">"{{ $shootingScript->penyebutan_audiens }}"</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shooting Script -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-500 to-red-600">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-film mr-3"></i>
                        Shooting Script
                    </h2>
                    <p class="text-orange-100 text-sm mt-1">Script lengkap siap untuk produksi video</p>
                </div>

                <div class="p-6">
                    @php
                        $totalDurasi = array_sum(array_column($scriptArray, 'durasi'));
                        $hookCount = count(array_filter($scriptArray, fn($item) => $item['kategori'] === 'Hook'));
                        $isiCount = count(array_filter($scriptArray, fn($item) => $item['kategori'] === 'Isi'));
                        $penutupCount = count(array_filter($scriptArray, fn($item) => $item['kategori'] === 'Penutup'));
                        $ctaCount = count(array_filter($scriptArray, fn($item) => $item['kategori'] === 'CTA'));
                    @endphp

                        <!-- Script Statistics -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-bold text-gray-800">{{ count($scriptArray) }}</div>
                            <div class="text-xs text-gray-600">Total Scene</div>
                        </div>
                        <div class="text-center p-3 bg-red-50 rounded-lg">
                            <div class="text-lg font-bold text-red-600">{{ $hookCount }}</div>
                            <div class="text-xs text-gray-600">Hook</div>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-lg font-bold text-blue-600">{{ $isiCount }}</div>
                            <div class="text-xs text-gray-600">Isi</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-lg font-bold text-green-600">{{ $penutupCount }}</div>
                            <div class="text-xs text-gray-600">Penutup</div>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <div class="text-lg font-bold text-orange-600">{{ $totalDurasi }}s</div>
                            <div class="text-xs text-gray-600">Total Durasi</div>
                        </div>
                    </div>

                    <!-- Script Timeline -->
                    <div class="space-y-4">
                        @foreach($scriptArray as $index => $scene)
                            <div class="relative">
                                <!-- Timeline Line -->
                                @if(!$loop->last)
                                    <div class="absolute left-6 top-16 w-0.5 h-8
                                    @if($scene['kategori'] === 'Hook') bg-red-300
                                    @elseif($scene['kategori'] === 'Isi') bg-blue-300
                                    @elseif($scene['kategori'] === 'Penutup') bg-green-300
                                    @else bg-orange-300 @endif"></div>
                                @endif

                                <!-- Scene Card -->
                                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200">
                                    <!-- Scene Number & Category -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold
                                        @if($scene['kategori'] === 'Hook') bg-gradient-to-r from-red-500 to-red-600
                                        @elseif($scene['kategori'] === 'Isi') bg-gradient-to-r from-blue-500 to-blue-600
                                        @elseif($scene['kategori'] === 'Penutup') bg-gradient-to-r from-green-500 to-green-600
                                        @else bg-gradient-to-r from-orange-500 to-orange-600 @endif">
                                            {{ $scene['no'] }}
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                            @if($scene['kategori'] === 'Hook') bg-red-100 text-red-700
                                            @elseif($scene['kategori'] === 'Isi') bg-blue-100 text-blue-700
                                            @elseif($scene['kategori'] === 'Penutup') bg-green-100 text-green-700
                                            @else bg-orange-100 text-orange-700 @endif">
                                                {{ $scene['kategori'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Scene Content -->
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-semibold text-gray-800">Scene {{ $scene['no'] }}</h3>
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $scene['durasi'] }} detik
                                                </span>
                                                <button onclick="copyScript({{ $index }})" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Script Text -->
                                        <div class="bg-gray-50 p-4 rounded-lg border-l-4
                                        @if($scene['kategori'] === 'Hook') border-red-400
                                        @elseif($scene['kategori'] === 'Isi') border-blue-400
                                        @elseif($scene['kategori'] === 'Penutup') border-green-400
                                        @else border-orange-400 @endif">
                                            <p class="text-gray-800 leading-relaxed script-text" id="script-{{ $index }}">{{ $scene['script'] }}</p>
                                        </div>

                                        <!-- Visual Notes -->
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-start">
                                                <i class="fas fa-camera text-yellow-600 mr-2 mt-1 flex-shrink-0"></i>
                                                <div>
                                                    <p class="text-sm font-semibold text-yellow-800 mb-1">Catatan Visual:</p>
                                                    <p class="text-sm text-yellow-700">
                                                        @if($scene['kategori'] === 'Hook')
                                                            Close-up wajah dengan ekspresi menarik, pencahayaan bagus untuk menarik perhatian
                                                        @elseif($scene['kategori'] === 'Isi')
                                                            Medium shot dengan gesture yang mendukung narasi, bisa tambahkan visual pendukung
                                                        @elseif($scene['kategori'] === 'Penutup')
                                                            Shot yang menunjukkan kepercayaan diri, bisa wide shot atau medium shot
                                                        @else
                                                            Clear shot dengan gesture ajakan bertindak yang tegas dan friendly
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Script Siap Diproduksi!</h3>
                        <p class="text-gray-600 text-sm">Shooting script lengkap dengan timing dan visual notes</p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
{{--                        <button onclick="downloadScript()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">--}}
{{--                            <i class="fas fa-download mr-2"></i>Download PDF--}}
{{--                        </button>--}}
                        <button onclick="copyAllScript()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300">
                            <i class="fas fa-copy mr-2"></i>Copy All Script
                        </button>
{{--                        <a href="{{ route('front.shooting.form', $contentIdea->id) }}"--}}
{{--                           class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg font-semibold hover:from-orange-600 hover:to-red-700 transition-all duration-300 text-center">--}}
{{--                            <i class="fas fa-sync-alt mr-2"></i>Generate Ulang--}}
{{--                        </a>--}}
                        <a href="{{ route('front.content.form', $session->id) }}"
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-semibold transition-all duration-300 text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Production Tips -->
            <div class="mt-8 grid md:grid-cols-2 gap-6">
                <!-- Production Tips -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-video text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Tips Produksi</h4>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Siapkan pencahayaan yang baik, natural light terbaik</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Gunakan tripod untuk stabilitas kamera</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Record audio yang jernih, hindari noise</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Editing Tips -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4 mt-1">
                            <i class="fas fa-cut text-indigo-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Tips Editing</h4>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Perhatikan timing sesuai durasi script</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Tambahkan subtitle untuk accessibility</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                    <span>Gunakan musik background yang sesuai mood</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Timeline animations */
        .relative:hover .absolute {
            background-color: #4F46E5;
            transition: background-color 0.3s ease;
        }

        /* Script text styling */
        .script-text {
            font-family: 'Courier New', monospace;
            line-height: 1.6;
        }

        /* Copy button animation */
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
    </style>

    <script>
        // Script data for JavaScript functions
        const scriptData = @json($scriptArray);

        function copyScript(index) {
            const scriptText = document.getElementById(`script-${index}`).textContent;
            navigator.clipboard.writeText(scriptText).then(() => {
                // Show success feedback
                const button = event.target.closest('button');
                const originalIcon = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check text-green-500"></i>';

                setTimeout(() => {
                    button.innerHTML = originalIcon;
                }, 2000);
            });
        }

        function copyAllScript() {
            let fullScript = '';
            scriptData.forEach((scene, index) => {
                fullScript += `Scene ${scene.no} (${scene.kategori}) - ${scene.durasi}s:\n`;
                fullScript += `${scene.script}\n\n`;
            });

            navigator.clipboard.writeText(fullScript).then(() => {
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Script Tersalin!';
                button.classList.add('bg-green-500');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-500');
                }, 3000);
            });
        }

        function downloadScript() {
            // Implementation for PDF download
            alert('Fitur download PDF akan segera tersedia');
        }

        // Add fade-in animation to script cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.relative');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(card);
            });
        });
    </script>
@endsection
