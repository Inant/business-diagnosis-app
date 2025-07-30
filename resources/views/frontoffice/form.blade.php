@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-4 sm:py-6 lg:py-8">
        <div class="max-w-sm sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">Formulir Analisa Bisnis</h1>
                <p class="text-sm sm:text-base text-gray-600 px-4">Lengkapi setiap langkah dengan cermat untuk analisa yang optimal</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6 sm:mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Progress</span>
                    <span class="text-xs sm:text-sm font-medium text-gray-700">
                        <span id="current-step">1</span> dari <span id="total-steps">{{ count($questions) }}</span>
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 sm:h-3">
                    <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 sm:h-3 rounded-full transition-all duration-300" style="width: {{ 100 / count($questions) }}%"></div>
                </div>

                <!-- Progress Steps - Responsive Grid -->
                <div class="mt-3 sm:mt-4">
                    <div class="flex justify-between items-center overflow-x-auto pb-2 sm:pb-0">
                        @foreach($questions as $index => $q)
                            <div class="flex flex-col items-center flex-shrink-0 mx-1 sm:mx-0">
                                <div id="step-{{ $index + 1 }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold transition-all duration-300 {{ $index === 0 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                    {{ $index + 1 }}
                                </div>
                                <span class="text-xs text-gray-500 mt-1 hidden sm:block">Step {{ $index + 1 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden">
                <form method="POST" action="{{ route('front.form.submit') }}" id="multi-step-form">
                    @csrf

                    <!-- Steps Container -->
                    <div id="steps-container" class="relative">
                        @foreach($questions as $index => $q)
                            <div class="step {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                                <div class="p-4 sm:p-6 md:p-8 lg:p-12">
                                    <!-- Step Header -->
                                    <div class="mb-6 sm:mb-8">
                                        <div class="flex flex-col sm:flex-row items-center sm:items-start mb-4 sm:mb-6">
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl mb-3 sm:mb-0 sm:mr-4 md:mr-6 flex-shrink-0">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 text-center sm:text-left">
                                                <p class="text-xs sm:text-sm text-gray-500 font-medium mb-2">
                                                    Step {{ $index + 1 }} dari {{ count($questions) }}
                                                </p>
                                                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 leading-tight sm:leading-relaxed">
                                                    {{ $q->question }}
                                                </h2>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Input Field -->
                                    <div class="mb-6 sm:mb-8">
                                        <label class="block text-gray-700 font-semibold mb-3 text-base sm:text-lg">
                                            Jawaban Anda <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            name="answers[{{ $q->id }}]"
                                            rows="5"
                                            class="w-full border-2 border-gray-200 rounded-lg sm:rounded-xl px-3 sm:px-4 py-3 sm:py-4 text-sm sm:text-base focus:outline-none focus:border-blue-500 focus:ring-2 sm:focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none"
                                            placeholder="Masukkan jawaban Anda di sini..."
                                            required></textarea>
                                        <div class="mt-2 sm:mt-3 flex justify-between items-center text-xs sm:text-sm">
                                            <span class="text-gray-500">Minimal 10 karakter</span>
                                            <span class="text-gray-400"><span class="char-count">0</span> karakter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="bg-gray-50 px-4 sm:px-6 md:px-8 lg:px-12 py-4 sm:py-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                            <!-- Previous Button -->
                            <button type="button" id="prev-btn" class="w-full sm:w-auto order-2 sm:order-1 px-4 sm:px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base" disabled>
                                <i class="fas fa-arrow-left mr-2"></i>Sebelumnya
                            </button>

                            <!-- Step Indicators - Only show on larger screens -->
                            <div class="hidden sm:flex space-x-2 order-1 sm:order-2">
                                @foreach($questions as $index => $q)
                                    <button type="button" class="step-indicator w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-300' }}" data-step="{{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <!-- Next/Submit Button -->
                            <div class="w-full sm:w-auto order-3">
                                <button type="button" id="next-btn" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 text-sm sm:text-base">
                                    Selanjutnya<i class="fas fa-arrow-right ml-2"></i>
                                </button>

                                <button type="submit" id="submit-btn" class="w-full sm:w-auto px-6 sm:px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 hidden text-sm sm:text-base">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit Jawaban
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-6 sm:mt-8 bg-white rounded-lg sm:rounded-xl shadow-md sm:shadow-lg p-4 sm:p-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3 sm:mr-4 mt-1 flex-shrink-0">
                        <i class="fas fa-lightbulb text-yellow-600 text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">Tips Pengisian</h3>
                        <ul class="text-gray-600 text-xs sm:text-sm space-y-1">
                            <li>• Berikan jawaban yang detail dan spesifik</li>
                            <li>• Gunakan data dan fakta yang akurat</li>
                            <li>• Jawaban minimal 10 karakter</li>
                            <li>• Anda dapat kembali ke step sebelumnya untuk mengedit jawaban</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl">
            <div class="mb-6">
                <div class="w-16 h-16 mx-auto mb-4">
                    <div class="loading-spinner"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Sedang Memproses</h3>
                <p class="text-gray-600 text-sm">Mohon tunggu, kami sedang menganalisa...</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="loading-progress bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full"></div>
            </div>
        </div>
    </div>

    <style>
        .step {
            display: none;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.4s ease-in-out;
        }

        .step.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .step.prev {
            transform: translateX(-50px);
        }

        /* Custom scrollbar */
        textarea::-webkit-scrollbar {
            width: 4px;
        }

        @media (min-width: 640px) {
            textarea::-webkit-scrollbar {
                width: 6px;
            }
        }

        textarea::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation untuk step indicators */
        .step-indicator {
            cursor: pointer;
        }

        .step-indicator:hover {
            transform: scale(1.2);
        }

        /* Loading animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Better text alignment and spacing */
        .step h2 {
            word-wrap: break-word;
            hyphens: auto;
        }

        /* Touch-friendly buttons on mobile */
        @media (max-width: 639px) {
            .step-indicator {
                min-width: 44px;
                min-height: 44px;
            }

            button {
                min-height: 44px;
            }
        }

        /* Improved horizontal scroll for progress steps */
        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        /* Better spacing for mobile */
        @media (max-width: 639px) {
            .step .flex-col.sm\:flex-row {
                gap: 0.75rem;
            }
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }

        /* Better focus states for accessibility */
        button:focus,
        textarea:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Smooth transitions for all interactive elements */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Loading Modal */
        #loading-modal {
            backdrop-filter: blur(4px);
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        #loading-modal.show {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 64px;
            height: 64px;
            border: 4px solid #e5e7eb;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            position: relative;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Loading Progress Bar */
        .loading-progress {
            width: 0%;
            transition: width 3s ease-in-out;
        }

        /* Modal Animation */
        #loading-modal.show .bg-white {
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
            border: 2px solid rgba(59, 130, 246, 0.2);
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
        document.addEventListener('DOMContentLoaded', function() {
            // Clear all localStorage data for this form on page load
            clearFormLocalStorage();

            let currentStep = 1;
            const totalSteps = {{ count($questions) }};
            const steps = document.querySelectorAll('.step');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('progress-bar');
            const currentStepSpan = document.getElementById('current-step');
            const stepIndicators = document.querySelectorAll('.step-indicator');

            // Flag untuk mengontrol beforeunload
            let isSubmitting = false;

            // Function to clear localStorage for form
            function clearFormLocalStorage() {
                const keys = Object.keys(localStorage);
                keys.forEach(key => {
                    if (key.startsWith('form_answer_')) {
                        localStorage.removeItem(key);
                    }
                });
            }

            // Function to show loading modal
            function showLoadingModal() {
                const modal = document.getElementById('loading-modal');
                modal.classList.remove('hidden');

                // Force reflow untuk memastikan perubahan class diterapkan
                modal.offsetHeight;

                modal.classList.add('show');

                // Prevent scrolling while modal is open
                document.body.style.overflow = 'hidden';

                // Trigger progress bar animation
                setTimeout(() => {
                    const progressBar = modal.querySelector('.loading-progress');
                    progressBar.style.width = '95%';
                }, 100);
            }

            // Function to hide loading modal (jika diperlukan)
            function hideLoadingModal() {
                const modal = document.getElementById('loading-modal');
                modal.classList.remove('show');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Character counter
            document.querySelectorAll('textarea').forEach(textarea => {
                const charCount = textarea.parentNode.querySelector('.char-count');

                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });

                charCount.textContent = '0';
            });

            function updateUI() {
                const progress = (currentStep / totalSteps) * 100;
                progressBar.style.width = progress + '%';
                currentStepSpan.textContent = currentStep;

                // Update step circles in progress
                for (let i = 1; i <= totalSteps; i++) {
                    const stepCircle = document.getElementById('step-' + i);
                    if (stepCircle) {
                        if (i <= currentStep) {
                            stepCircle.classList.remove('bg-gray-300', 'text-gray-600');
                            stepCircle.classList.add('bg-blue-500', 'text-white');
                        } else {
                            stepCircle.classList.remove('bg-blue-500', 'text-white');
                            stepCircle.classList.add('bg-gray-300', 'text-gray-600');
                        }
                    }
                }

                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    if (index + 1 <= currentStep) {
                        indicator.classList.remove('bg-gray-300');
                        indicator.classList.add('bg-blue-500');
                    } else {
                        indicator.classList.remove('bg-blue-500');
                        indicator.classList.add('bg-gray-300');
                    }
                });

                // Update buttons
                prevBtn.disabled = currentStep === 1;
                prevBtn.style.opacity = currentStep === 1 ? '0.5' : '1';

                if (currentStep === totalSteps) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'block';
                } else {
                    nextBtn.style.display = 'block';
                    submitBtn.style.display = 'none';
                }
            }

            function showStep(stepNumber) {
                steps.forEach((step, index) => {
                    step.classList.remove('active');
                    if (index + 1 < stepNumber) {
                        step.classList.add('prev');
                    } else {
                        step.classList.remove('prev');
                    }
                });

                setTimeout(() => {
                    const targetStep = document.querySelector(`.step[data-step="${stepNumber}"]`);
                    if (targetStep) {
                        targetStep.classList.add('active');

                        // Auto-focus textarea on mobile for better UX
                        if (window.innerWidth <= 768) {
                            setTimeout(() => {
                                const textarea = targetStep.querySelector('textarea');
                                if (textarea) {
                                    textarea.focus();
                                }
                            }, 400);
                        }
                    }
                }, 150);

                currentStep = stepNumber;
                updateUI();

                // Smooth scroll to top with offset for mobile
                const offset = window.innerWidth <= 768 ? 100 : 0;
                const targetPosition = document.getElementById('steps-container').offsetTop - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }

            function validateCurrentStep() {
                const currentStepElement = document.querySelector(`.step[data-step="${currentStep}"]`);
                const textarea = currentStepElement.querySelector('textarea');

                const existingError = currentStepElement.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                if (textarea.value.trim().length < 10) {
                    // Scroll textarea into view on mobile
                    if (window.innerWidth <= 768) {
                        textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    textarea.focus();
                    textarea.style.borderColor = '#ef4444';
                    textarea.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';

                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message text-red-500 text-xs sm:text-sm mt-1 flex items-center';
                    errorMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Jawaban minimal 10 karakter';
                    textarea.parentNode.appendChild(errorMsg);

                    setTimeout(() => {
                        textarea.style.borderColor = '#d1d5db';
                        textarea.style.boxShadow = '';
                        if (errorMsg && errorMsg.parentNode) {
                            errorMsg.remove();
                        }
                    }, 3000);

                    return false;
                }

                textarea.style.borderColor = '#d1d5db';
                textarea.style.boxShadow = '';
                return true;
            }

            // Navigation event listeners
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (validateCurrentStep() && currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            });

            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            stepIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetStep = index + 1;

                    if (targetStep <= currentStep) {
                        showStep(targetStep);
                    } else if (targetStep === currentStep + 1 && validateCurrentStep()) {
                        showStep(targetStep);
                    }
                });
            });

            // Touch support for swipe navigation on mobile
            let touchStartX = 0;
            let touchEndX = 0;

            document.addEventListener('touchstart', function(e) {
                if (e.target.tagName.toLowerCase() !== 'textarea') {
                    touchStartX = e.changedTouches[0].screenX;
                }
            });

            document.addEventListener('touchend', function(e) {
                if (e.target.tagName.toLowerCase() !== 'textarea') {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const swipeDistance = touchEndX - touchStartX;

                if (Math.abs(swipeDistance) > swipeThreshold) {
                    if (swipeDistance > 0 && currentStep > 1) {
                        // Swipe right - go to previous step
                        showStep(currentStep - 1);
                    } else if (swipeDistance < 0 && currentStep < totalSteps && validateCurrentStep()) {
                        // Swipe left - go to next step
                        showStep(currentStep + 1);
                    }
                }
            }

            // Keyboard navigation (only on non-mobile)
            document.addEventListener('keydown', function(e) {
                if (window.innerWidth > 768 && document.activeElement.tagName.toLowerCase() !== 'textarea') {
                    if (e.key === 'ArrowRight' && currentStep < totalSteps && validateCurrentStep()) {
                        e.preventDefault();
                        showStep(currentStep + 1);
                    } else if (e.key === 'ArrowLeft' && currentStep > 1) {
                        e.preventDefault();
                        showStep(currentStep - 1);
                    }
                }
            });

            // SUBMIT HANDLER - DIPERBAIKI
            document.getElementById('multi-step-form').addEventListener('submit', function(e) {
                console.log('Form submit triggered');

                // Set flag bahwa kita sedang submit
                isSubmitting = true;

                let allValid = true;
                let firstInvalidStep = null;

                steps.forEach((step, index) => {
                    const textarea = step.querySelector('textarea');
                    if (textarea.value.trim().length < 10) {
                        allValid = false;
                        if (firstInvalidStep === null) {
                            firstInvalidStep = index + 1;
                        }
                    }
                });

                if (!allValid) {
                    e.preventDefault();
                    isSubmitting = false; // Reset flag jika validasi gagal
                    alert('Mohon lengkapi semua jawaban dengan minimal 10 karakter');

                    if (firstInvalidStep) {
                        showStep(firstInvalidStep);
                    }
                    return false;
                }

                console.log('Showing loading modal');
                showLoadingModal();

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                submitBtn.disabled = true;

                // Clear localStorage setelah delay
                setTimeout(() => {
                    clearFormLocalStorage();
                }, 1000);

                // Form akan submit secara normal karena kita tidak preventDefault
            });

            // Local storage handlers
            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    const key = 'form_answer_' + textarea.name;
                    localStorage.setItem(key, this.value);
                });
            });

            let formHasData = false;

            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    formHasData = Array.from(document.querySelectorAll('textarea'))
                        .some(ta => ta.value.trim().length > 0);
                });
            });

            // DIPERBAIKI: beforeunload hanya aktif jika TIDAK sedang submit
            window.addEventListener('beforeunload', function(e) {
                // Jika sedang submit, jangan tampilkan konfirmasi
                if (isSubmitting) {
                    return;
                }

                // Hanya tampilkan konfirmasi jika ada data dan TIDAK sedang submit
                if (formHasData) {
                    const message = 'Anda memiliki data yang belum disimpan. Yakin ingin meninggalkan halaman?';
                    e.returnValue = message;
                    return message;
                }
            });

            // Initialize
            updateUI();

            // Focus management for better UX
            const firstTextarea = document.querySelector('.step.active textarea');
            if (firstTextarea && window.innerWidth > 768) {
                setTimeout(() => firstTextarea.focus(), 500);
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                updateUI();
            });
        });
    </script>

@endsection
