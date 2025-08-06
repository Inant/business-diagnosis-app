@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-100 py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full mb-3 sm:mb-4">
                    <i class="fas fa-calendar-check text-white text-lg sm:text-2xl"></i>
                </div>
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2">Kalender Konten Anda</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Konten yang telah disesuaikan dengan analisa bisnis Anda</p>
            </div>

            <!-- Content Plan Info Card -->
            <div class="mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-4 lg:space-y-0">
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold mb-1">Plan #{{ $contentPlan->id }}</h3>
                            <p class="text-sm sm:text-base text-pink-100 mb-2">
                                Dibuat pada {{ $contentPlan->created_at->format('d F Y') }} - {{ $contentPlan->created_at->format('H:i') }} WIB
                            </p>
                            @if($contentPlan->tujuan_pembuatan_konten)
                                <div class="bg-white bg-opacity-20 rounded-lg p-3 mt-3">
                                    <p class="text-xs sm:text-sm font-medium">Tujuan: {{ $contentPlan->tujuan_pembuatan_konten }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full lg:w-auto">
                            <div class="text-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                                <div class="text-lg sm:text-xl font-bold">{{ $contentPlan->contentIdeas->count() }}</div>
                                <div class="text-xs sm:text-sm text-pink-100">Konten</div>
                            </div>
                            <div class="text-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                                <div class="text-lg sm:text-xl font-bold">{{ $contentPlan->days }}</div>
                                <div class="text-xs sm:text-sm text-pink-100">Hari</div>
                            </div>
                            <div class="text-center bg-white bg-opacity-20 rounded-lg px-3 py-2">
                                <div class="text-sm sm:text-base font-bold">{{ $contentPlan->formatted_cost }}</div>
                                <div class="text-xs sm:text-sm text-pink-100">Biaya</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 sm:mb-8 space-y-3 sm:space-y-0">
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <a href="{{ route('front.content.history') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                    <a href="{{ route('front.content.create') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Generate Lagi
                    </a>
                </div>
                <div class="flex space-x-2">
                    <button onclick="exportToCSV()" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors duration-300 text-sm">
                        <i class="fas fa-download mr-2"></i>
                        Export CSV
                    </button>
                    <button onclick="printContent()" class="inline-flex items-center justify-center px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold hover:bg-purple-600 transition-colors duration-300 text-sm">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </button>
                </div>
            </div>

            <!-- Filter dan View Toggle -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-3 sm:space-y-0">
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterByPilar('all')" class="filter-btn active px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-xs font-medium hover:bg-gray-300 transition-colors duration-200">
                        Semua
                    </button>
                    <button onclick="filterByPilar('Edukasi')" class="filter-btn px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium hover:bg-blue-200 transition-colors duration-200">
                        Edukasi
                    </button>
                    <button onclick="filterByPilar('Interaksi')" class="filter-btn px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium hover:bg-green-200 transition-colors duration-200">
                        Interaksi
                    </button>
                    <button onclick="filterByPilar('Inspirasi')" class="filter-btn px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium hover:bg-purple-200 transition-colors duration-200">
                        Inspirasi
                    </button>
                </div>
                <div class="flex space-x-2">
                    <button onclick="toggleView('timeline')" id="timeline-btn" class="view-btn active px-3 py-1 bg-pink-500 text-white rounded-lg text-sm font-medium hover:bg-pink-600 transition-colors duration-200">
                        <i class="fas fa-list mr-1"></i>Timeline
                    </button>
                    <button onclick="toggleView('grid')" id="grid-btn" class="view-btn px-3 py-1 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-th mr-1"></i>Grid
                    </button>
                    <button onclick="toggleView('calendar')" id="calendar-btn" class="view-btn px-3 py-1 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors duration-200">
                        <i class="fas fa-calendar mr-1"></i>Kalender
                    </button>
                </div>
            </div>

            <!-- Timeline View (Default) -->
            <div id="timeline-view" class="content-view">
                <div class="space-y-4 sm:space-y-6">
                    @foreach($contentPlan->contentIdeas->sortBy('hari_ke') as $index => $content)
                        <div class="content-item bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-pilar="{{ $content->pilar_konten }}" data-day="{{ $content->hari_ke }}">
                            <!-- Timeline Connector -->
                            @if(!$loop->last)
                                <div class="absolute left-8 top-20 w-0.5 h-16 bg-gradient-to-b from-pink-300 to-purple-300 z-10 hidden lg:block"></div>
                            @endif

                            <div class="flex flex-col lg:flex-row">
                                <!-- Day Indicator -->
                                <div class="lg:w-24 bg-gradient-to-br from-pink-500 to-purple-600 text-white p-4 lg:p-6 flex flex-row lg:flex-col items-center justify-center text-center relative">
                                    <div class="absolute top-2 right-2 lg:top-4 lg:right-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white">
                                            {{ $content->pilar_konten }}
                                        </span>
                                    </div>
                                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1 lg:mb-2">{{ $content->hari_ke }}</div>
                                    <div class="text-xs sm:text-sm text-pink-100">Hari</div>
                                    <div class="text-xs text-pink-100 mt-1 lg:mt-2">
                                        {{ now()->addDays($content->hari_ke - 1)->format('M d') }}
                                    </div>
                                </div>

                                <!-- Content Details -->
                                <div class="flex-1 p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 leading-tight">{{ $content->judul_konten }}</h3>
                                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $content->pilar_konten == 'Edukasi' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $content->pilar_konten == 'Interaksi' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $content->pilar_konten == 'Inspirasi' ? 'bg-purple-100 text-purple-800' : '' }}">
                                                    <i class="fas fa-{{ $content->pilar_konten == 'Edukasi' ? 'graduation-cap' : ($content->pilar_konten == 'Interaksi' ? 'comments' : 'lightbulb') }} mr-1"></i>
                                                    {{ $content->pilar_konten }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                    <i class="fas fa-camera mr-1"></i>
                                                    {{ $content->rekomendasi_format }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 mt-2 sm:mt-0">
                                            <button onclick="copyContent({{ $content->id }})" class="p-2 text-gray-500 hover:text-purple-600 transition-colors duration-200" title="Copy konten">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button onclick="toggleExpand({{ $content->id }})" class="p-2 text-gray-500 hover:text-purple-600 transition-colors duration-200" title="Expand/Collapse">
                                                <i class="fas fa-chevron-down expand-icon" id="icon-{{ $content->id }}"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Hook -->
                                    <div class="mb-4">
                                        <div class="bg-gradient-to-r from-pink-50 to-purple-50 border-l-4 border-pink-400 p-3 rounded-r-lg">
                                            <h4 class="text-sm font-semibold text-pink-800 mb-1">🎯 Hook Pembuka:</h4>
                                            <p class="text-sm text-pink-700 italic">"{{ $content->hook }}"</p>
                                        </div>
                                    </div>

                                    <!-- Expandable Content -->
                                    <div id="content-{{ $content->id }}" class="expandable-content hidden">
                                        <!-- Script Poin Utama -->
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                                <i class="fas fa-list mr-2 text-blue-500"></i>
                                                Poin-poin Utama:
                                            </h4>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                @if(is_array($content->script_poin_utama))
                                                    <ul class="space-y-2">
                                                        @foreach($content->script_poin_utama as $poin)
                                                            <li class="flex items-start">
                                                                <span class="inline-block w-2 h-2 bg-purple-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                                <span class="text-sm text-gray-700">{{ $poin }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-sm text-gray-700">{{ $content->script_poin_utama }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Call to Action -->
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                                <i class="fas fa-bullhorn mr-2 text-orange-500"></i>
                                                Call to Action:
                                            </h4>
                                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                                <p class="text-sm text-orange-800 font-medium">{{ $content->call_to_action }}</p>
                                            </div>
                                        </div>

                                        <!-- Format Rekomendasi -->
                                        <div class="flex flex-wrap gap-2">
                                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-2 text-center">
                                                <div class="text-xs text-indigo-600 font-medium">Format</div>
                                                <div class="text-sm text-indigo-800">{{ $content->rekomendasi_format }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                                        <button onclick="showContentPreview({{ $content->id }})" class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-200 transition-colors duration-200">
                                            <i class="fas fa-eye mr-1"></i>Preview
                                        </button>
                                        <button onclick="schedulePost({{ $content->id }})" class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-200 transition-colors duration-200">
                                            <i class="fas fa-calendar-plus mr-1"></i>Jadwalkan
                                        </button>
                                        <button onclick="editContent({{ $content->id }})" class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200 transition-colors duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Grid View -->
            <div id="grid-view" class="content-view hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($contentPlan->contentIdeas->sortBy('hari_ke') as $content)
                        <div class="content-item bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-pilar="{{ $content->pilar_konten }}" data-day="{{ $content->hari_ke }}">
                            <!-- Card Header -->
                            <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                            <span class="font-bold text-lg">{{ $content->hari_ke }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm text-pink-100">Hari {{ $content->hari_ke }}</div>
                                            <div class="text-xs text-pink-200">{{ now()->addDays($content->hari_ke - 1)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full">{{ $content->pilar_konten }}</span>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-2 text-sm leading-tight">{{ Str::limit($content->judul_konten, 60) }}</h3>
                                <p class="text-xs text-gray-600 mb-3 italic">"{{ Str::limit($content->hook, 80) }}"</p>

                                <div class="flex items-center justify-between">
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">{{ $content->rekomendasi_format }}</span>
                                    <button onclick="toggleExpand({{ $content->id }})" class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendar View -->
            <div id="calendar-view" class="content-view hidden">
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                    <div id="content-calendar" class="calendar-container">
                        <!-- Calendar akan di-generate oleh JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                    Ringkasan Konten
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-1">
                            {{ $contentPlan->contentIdeas->where('pilar_konten', 'Edukasi')->count() }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-600">Edukasi</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-green-600 mb-1">
                            {{ $contentPlan->contentIdeas->where('pilar_konten', 'Interaksi')->count() }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-600">Interaksi</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-purple-600 mb-1">
                            {{ $contentPlan->contentIdeas->where('pilar_konten', 'Inspirasi')->count() }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-600">Inspirasi</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-gray-600 mb-1">
                            {{ $contentPlan->contentIdeas->count() }}
                        </div>
                        <div class="text-xs sm:text-sm text-gray-600">Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Preview Modal -->
    <div id="content-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Preview Konten</h3>
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="preview-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <style>
        .expandable-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .expandable-content.show {
            max-height: 1000px;
        }

        .expand-icon {
            transition: transform 0.3s ease;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }

        .filter-btn.active {
            background-color: #8b5cf6 !important;
            color: white !important;
        }

        .view-btn.active {
            background-color: #ec4899 !important;
            color: white !important;
        }

        .content-item {
            transition: all 0.3s ease;
        }

        .content-item.hidden {
            display: none;
        }

        .calendar-container {
            min-height: 400px;
        }

        .calendar-day {
            border: 1px solid #e5e7eb;
            min-height: 120px;
            padding: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            background-color: #f9fafb;
        }

        .calendar-day.has-content {
            background-color: #fef3ff;
            border-color: #d8b4fe;
        }

        .calendar-content {
            font-size: 0.75rem;
            line-height: 1.2;
        }

        @media (max-width: 640px) {
            .calendar-day {
                min-height: 80px;
                padding: 4px;
            }
        }
    </style>

    <script>
        // Global variables
        let currentView = 'timeline';
        let currentFilter = 'all';
        const contentData = @json($contentPlan->contentIdeas);

        // Toggle expand/collapse content
        function toggleExpand(contentId) {
            const content = document.getElementById(`content-${contentId}`);
            const icon = document.getElementById(`icon-${contentId}`);

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                content.classList.add('show');
                icon.classList.add('rotated');
            } else {
                content.classList.add('hidden');
                content.classList.remove('show');
                icon.classList.remove('rotated');
            }
        }

        // Filter content by pilar
        function filterByPilar(pilar) {
            const items = document.querySelectorAll('.content-item');
            const buttons = document.querySelectorAll('.filter-btn');

            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Filter items
            items.forEach(item => {
                const itemPilar = item.getAttribute('data-pilar');
                if (pilar === 'all' || itemPilar === pilar) {
                    item.style.display = '';
                    item.classList.remove('hidden');
                } else {
                    item.style.display = 'none';
                    item.classList.add('hidden');
                }
            });

            currentFilter = pilar;
        }

        // Toggle view mode
        function toggleView(view) {
            const views = ['timeline', 'grid', 'calendar'];
            const buttons = document.querySelectorAll('.view-btn');

            // Hide all views
            views.forEach(v => {
                document.getElementById(`${v}-view`).classList.add('hidden');
                document.getElementById(`${v}-btn`).classList.remove('active');
            });

            // Show selected view
            document.getElementById(`${view}-view`).classList.remove('hidden');
            document.getElementById(`${view}-btn`).classList.add('active');

            currentView = view;

            // Generate calendar if calendar view is selected
            if (view === 'calendar') {
                generateCalendar();
            }
        }

        // Generate calendar view
        function generateCalendar() {
            const calendarContainer = document.getElementById('content-calendar');
            const startDate = new Date();
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            let calendarHTML = '<div class="grid grid-cols-7 gap-1 mb-4">';

            // Header days
            days.forEach(day => {
                calendarHTML += `<div class="text-center font-semibold text-gray-700 p-2">${day}</div>`;
            });

            // Calendar days
            for (let i = 1; i <= {{ $contentPlan->days }}; i++) {
                const currentDate = new Date(startDate);
                currentDate.setDate(startDate.getDate() + i - 1);

                const content = contentData.find(c => c.hari_ke === i);
                const hasContent = content ? 'has-content' : '';

                calendarHTML += `
                    <div class="calendar-day ${hasContent}" onclick="showCalendarContent(${i})">
                        <div class="font-semibold text-gray-800">${i}</div>
                        <div class="text-xs text-gray-500">${currentDate.toLocaleDateString('id-ID', {month: 'short', day: 'numeric'})}</div>
                        ${content ? `
                            <div class="calendar-content mt-1">
                                <div class="text-xs font-medium text-purple-700 truncate">${content.judul_konten}</div>
                                <div class="text-xs text-gray-600">${content.pilar_konten}</div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            calendarHTML += '</div>';
            calendarContainer.innerHTML = calendarHTML;
        }

        // Show content for specific calendar day
        function showCalendarContent(day) {
            const content = contentData.find(c => c.hari_ke === day);
            if (content) {
                showContentPreview(content.id);
            }
        }

        // Copy content to clipboard
        function copyContent(contentId) {
            const content = contentData.find(c => c.id === contentId);
            if (content) {
                const textToCopy = `
${content.judul_konten}

Hook: ${content.hook}

Poin Utama:
${Array.isArray(content.script_poin_utama) ? content.script_poin_utama.map(p => `• ${p}`).join('\n') : content.script_poin_utama}

CTA: ${content.call_to_action}

Format: ${content.rekomendasi_format}
                `.trim();

                navigator.clipboard.writeText(textToCopy).then(() => {
                    showNotification('Konten berhasil disalin!', 'success');
                }).catch(() => {
                    showNotification('Gagal menyalin konten', 'error');
                });
            }
        }
        // Show content preview modal
        function showContentPreview(contentId) {
            const content = contentData.find(c => c.id === contentId);
            if (content) {
                const modal = document.getElementById('content-preview-modal');
                const previewContent = document.getElementById('preview-content');

                previewContent.innerHTML = `
                <div class="space-y-4">
                    <div class="border-b pb-4">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">${content.judul_konten}</h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">${content.pilar_konten}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">${content.rekomendasi_format}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-pink-100 text-pink-800">Hari ${content.hari_ke}</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-pink-50 to-purple-50 border-l-4 border-pink-400 p-4 rounded-r-lg">
                        <h5 class="font-semibold text-pink-800 mb-2">🎯 Hook Pembuka:</h5>
                        <p class="text-pink-700 italic">"${content.hook}"</p>
                    </div>

                    <div>
                        <h5 class="font-semibold text-gray-800 mb-2">📝 Poin-poin Utama:</h5>
                        <div class="bg-gray-50 rounded-lg p-4">
                            ${Array.isArray(content.script_poin_utama)
                    ? content.script_poin_utama.map(poin => `<p class="mb-2">• ${poin}</p>`).join('')
                    : `<p>${content.script_poin_utama}</p>`
                }
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h5 class="font-semibold text-orange-800 mb-2">📢 Call to Action:</h5>
                        <p class="text-orange-700 font-medium">${content.call_to_action}</p>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button onclick="copyContent(${content.id})" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors duration-200">
                            <i class="fas fa-copy mr-2"></i>Copy Konten
                        </button>
                        <button onclick="editContent(${content.id})" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                    </div>
                </div>
            `;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close preview modal
        function closePreviewModal() {
            const modal = document.getElementById('content-preview-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Schedule post (placeholder function)
        function schedulePost(contentId) {
            showNotification('Fitur jadwal posting akan segera hadir!', 'info');
        }

        // Edit content (placeholder function)
        function editContent(contentId) {
            showNotification('Fitur edit konten akan segera hadir!', 'info');
        }

        // Export to CSV
        function exportToCSV() {
            const csvContent = [
                ['Hari', 'Judul Konten', 'Pilar Konten', 'Hook', 'Poin Utama', 'CTA', 'Format'],
                ...contentData.map(content => [
                    content.hari_ke,
                    content.judul_konten,
                    content.pilar_konten,
                    content.hook,
                    Array.isArray(content.script_poin_utama) ? content.script_poin_utama.join('; ') : content.script_poin_utama,
                    content.call_to_action,
                    content.rekomendasi_format
                ])
            ];

            const csvString = csvContent.map(row =>
                row.map(field => `"${field.toString().replace(/"/g, '""')}"`).join(',')
            ).join('\n');

            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `kalender-konten-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('File CSV berhasil diunduh!', 'success');
        }

        // Print content
        function printContent() {
            const printWindow = window.open('', '_blank');
            const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Kalender Konten - Plan #{{ $contentPlan->id }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .content-item { margin-bottom: 30px; page-break-inside: avoid; }
                    .day-header { background: #8b5cf6; color: white; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
                    .content-section { margin-bottom: 15px; }
                    .hook { background: #fef3ff; border-left: 4px solid #ec4899; padding: 15px; margin-bottom: 15px; }
                    .poin-utama { background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
                    .cta { background: #fef3cd; border: 1px solid #f59e0b; padding: 15px; border-radius: 8px; }
                    ul { margin: 10px 0; padding-left: 20px; }
                    @media print { .no-print { display: none; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Kalender Konten Bisnis</h1>
                    <p>Plan #{{ $contentPlan->id }} - {{ $contentPlan->created_at->format('d F Y') }}</p>
                    ${contentPlan.tujuan_pembuatan_konten ? `<p><strong>Tujuan:</strong> {{ $contentPlan->tujuan_pembuatan_konten }}</p>` : ''}
                </div>

                ${contentData.map(content => `
                    <div class="content-item">
                        <div class="day-header">
                            <h2>Hari ${content.hari_ke} - ${content.judul_konten}</h2>
                            <p>Pilar: ${content.pilar_konten} | Format: ${content.rekomendasi_format}</p>
                        </div>

                        <div class="hook">
                            <h3>🎯 Hook Pembuka:</h3>
                            <p><em>"${content.hook}"</em></p>
                        </div>

                        <div class="poin-utama">
                            <h3>📝 Poin-poin Utama:</h3>
                            ${Array.isArray(content.script_poin_utama)
                ? `<ul>${content.script_poin_utama.map(poin => `<li>${poin}</li>`).join('')}</ul>`
                : `<p>${content.script_poin_utama}</p>`
            }
                        </div>

                        <div class="cta">
                            <h3>📢 Call to Action:</h3>
                            <p><strong>${content.call_to_action}</strong></p>
                        </div>
                    </div>
                `).join('')}
            </body>
            </html>
        `;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' :
                        type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
            notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(full)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside
            document.getElementById('content-preview-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePreviewModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePreviewModal();
                }
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';

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

            // Observe content items for scroll animation
            document.querySelectorAll('.content-item').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
                observer.observe(el);
            });
        });
    </script>
@endsection
