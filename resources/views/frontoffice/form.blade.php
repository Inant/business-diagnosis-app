@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Formulir Analisa Bisnis</h1>
                <p class="text-gray-600">Lengkapi setiap langkah dengan cermat untuk analisa yang optimal</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span class="text-sm font-medium text-gray-700"><span id="current-step">1</span> dari <span id="total-steps">{{ count($questions) }}</span></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-300" style="width: {{ 100 / count($questions) }}%"></div>
                </div>
                <div class="flex justify-between mt-2">
                    @foreach($questions as $index => $q)
                        <div class="flex flex-col items-center">
                            <div id="step-{{ $index + 1 }}" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 {{ $index === 0 ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-xs text-gray-500 mt-1 hidden md:block">Step {{ $index + 1 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form method="POST" action="{{ route('front.form.submit') }}" id="multi-step-form">
                    @csrf

                    <!-- Steps Container -->
                    <div id="steps-container" class="relative">
                        @foreach($questions as $index => $q)
                            <div class="step {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}">
                                <div class="p-8 md:p-12">
                                    <!-- Step Header -->
                                    <div class="mb-8">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <h2 class="text-2xl font-bold text-gray-800">{{ $q->title }}</h2>
                                                <p class="text-gray-500">Step {{ $index + 1 }} dari {{ count($questions) }}</p>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                            <p class="text-gray-700 leading-relaxed">{{ $q->question }}</p>
                                        </div>
                                    </div>

                                    <!-- Input Field -->
                                    <div class="mb-8">
                                        <label class="block text-gray-700 font-semibold mb-3">
                                            Jawaban Anda <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            name="answers[{{ $q->id }}]"
                                            rows="6"
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 resize-none"
                                            placeholder="Masukkan jawaban Anda di sini..."
                                            required></textarea>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Minimal 10 karakter</span>
                                            <span class="text-sm text-gray-400"><span class="char-count">0</span> karakter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="bg-gray-50 px-8 py-6 md:px-12">
                        <div class="flex justify-between items-center">
                            <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-arrow-left mr-2"></i>Sebelumnya
                            </button>

                            <div class="flex space-x-2">
                                @foreach($questions as $index => $q)
                                    <button type="button" class="step-indicator w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-300' }}" data-step="{{ $index + 1 }}"></button>
                                @endforeach
                            </div>

                            <button type="button" id="next-btn" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                                Selanjutnya<i class="fas fa-arrow-right ml-2"></i>
                            </button>

                            <button type="submit" id="submit-btn" class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 hidden">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Jawaban
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4 mt-1">
                        <i class="fas fa-lightbulb text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">Tips Pengisian</h3>
                        <ul class="text-gray-600 text-sm space-y-1">
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
            width: 6px;
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

            // Function to clear localStorage for form
            function clearFormLocalStorage() {
                // Get all keys from localStorage
                const keys = Object.keys(localStorage);

                // Remove only form-related keys
                keys.forEach(key => {
                    if (key.startsWith('form_answer_')) {
                        localStorage.removeItem(key);
                    }
                });
            }

            // Character counter
            document.querySelectorAll('textarea').forEach(textarea => {
                const charCount = textarea.parentNode.querySelector('.char-count');

                textarea.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });

                // Initialize counter with 0 (since form is always empty)
                charCount.textContent = '0';
            });

            function updateUI() {
                // Update progress bar
                const progress = (currentStep / totalSteps) * 100;
                progressBar.style.width = progress + '%';
                currentStepSpan.textContent = currentStep;

                // Update step circles in progress
                for (let i = 1; i <= totalSteps; i++) {
                    const stepCircle = document.getElementById('step-' + i);
                    if (i <= currentStep) {
                        stepCircle.classList.remove('bg-gray-300', 'text-gray-600');
                        stepCircle.classList.add('bg-blue-500', 'text-white');
                    } else {
                        stepCircle.classList.remove('bg-blue-500', 'text-white');
                        stepCircle.classList.add('bg-gray-300', 'text-gray-600');
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

                if (currentStep === totalSteps) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'inline-flex';
                } else {
                    nextBtn.style.display = 'inline-flex';
                    submitBtn.style.display = 'none';
                }
            }

            function showStep(stepNumber) {
                steps.forEach(step => {
                    step.classList.remove('active');
                });

                const targetStep = document.querySelector(`.step[data-step="${stepNumber}"]`);
                setTimeout(() => {
                    targetStep.classList.add('active');
                }, 100);

                currentStep = stepNumber;
                updateUI();
            }

            function validateCurrentStep() {
                const currentStepElement = document.querySelector(`.step[data-step="${currentStep}"]`);
                const textarea = currentStepElement.querySelector('textarea');

                if (textarea.value.trim().length < 10) {
                    textarea.focus();
                    textarea.style.borderColor = '#ef4444';

                    // Show error message
                    let errorMsg = currentStepElement.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message text-red-500 text-sm mt-1';
                        errorMsg.textContent = 'Jawaban minimal 10 karakter';
                        textarea.parentNode.appendChild(errorMsg);
                    }

                    setTimeout(() => {
                        textarea.style.borderColor = '#d1d5db';
                        if (errorMsg) errorMsg.remove();
                    }, 3000);

                    return false;
                }

                return true;
            }

            // Navigation event listeners
            nextBtn.addEventListener('click', function() {
                if (validateCurrentStep() && currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            });

            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            // Step indicator clicks
            stepIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    const targetStep = index + 1;
                    if (targetStep <= currentStep) {
                        showStep(targetStep);
                    }
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' && currentStep < totalSteps && validateCurrentStep()) {
                    showStep(currentStep + 1);
                } else if (e.key === 'ArrowLeft' && currentStep > 1) {
                    showStep(currentStep - 1);
                }
            });

            // Form submission
            document.getElementById('multi-step-form').addEventListener('submit', function(e) {
                // Validate all steps
                let allValid = true;
                steps.forEach((step, index) => {
                    const textarea = step.querySelector('textarea');
                    if (textarea.value.trim().length < 10) {
                        allValid = false;
                    }
                });

                if (!allValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua jawaban dengan minimal 10 karakter');
                    return;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                submitBtn.disabled = true;

                // Clear localStorage when form is successfully submitted
                clearFormLocalStorage();
            });

            // Optional: Save to localStorage during typing (but clear on page load)
            // This is useful if user accidentally refreshes during filling
            document.querySelectorAll('textarea').forEach(textarea => {
                // Don't load saved data on page load (form should always be empty)
                // But still save during typing for accidental refresh protection
                textarea.addEventListener('input', function() {
                    const key = 'form_answer_' + textarea.name;
                    localStorage.setItem(key, this.value);
                });
            });

            // Show confirmation if user tries to leave with unsaved data
            let formHasData = false;

            document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    formHasData = this.value.trim().length > 0 ||
                        Array.from(document.querySelectorAll('textarea'))
                            .some(ta => ta.value.trim().length > 0);
                });
            });

            window.addEventListener('beforeunload', function(e) {
                if (formHasData) {
                    const message = 'Anda memiliki data yang belum disimpan. Yakin ingin meninggalkan halaman?';
                    e.returnValue = message;
                    return message;
                }
            });

            // Initialize
            updateUI();
        });
    </script>
@endsection
