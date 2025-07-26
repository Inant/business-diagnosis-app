<nav x-data="{ open: false }" class="bg-gray-900 text-white shadow mb-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo dan Menu -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="font-bold text-lg text-blue-400">Business AI App</a>
                @auth
                    <div class="hidden md:flex space-x-6 ml-8">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('questions.index') }}" class="hover:text-blue-300">Manajemen Pertanyaan</a>
                            <a href="{{ route('backoffice.sessions') }}" class="hover:text-blue-300">Data Jawaban User</a>
                            <a href="{{ route('backoffice.usage_stats') }}" class="hover:text-blue-300">API Statistic</a>
                        @else
                            <a href="{{ route('front.form') }}" class="hover:text-blue-300">Isi Form Analisa</a>
{{--                            <a href="{{ route('front.form', ['category' => 2]) }}" class="hover:text-blue-300">Isi Form Analisa 2</a>--}}
                            <a href="{{ route('front.history') }}" class="hover:text-blue-300">Riwayat Analisa Saya</a>

                            <!-- MENU TAMBAHAN (Frontoffice Only) -->
                            <a href="https://product-prompt-generator.primtechdev.com/" target="_blank" class="hover:text-blue-300">Generator Foto Product</a>
                            <a href="https://social-media-prompt-generator.primtechdev.com/" target="_blank" class="hover:text-blue-300">Generator Social Media Post</a>
                            <a href="https://veo3-prompt-generator.primtechdev.com/" target="_blank" class="hover:text-blue-300">Prompt Generator VEO3</a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Auth/User -->
            <div class="hidden md:flex items-center">
                @auth
                    <span class="mr-4">Hi, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-300 mr-3">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-300">Register</a>
                @endauth
            </div>

            <!-- Hamburger (mobile) -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="md:hidden">
        <div class="px-4 pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('questions.index') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Manajemen Pertanyaan</a>
                    <a href="{{ route('backoffice.sessions') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Data Jawaban User</a>
                    <a href="{{ route('backoffice.usage_stats') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">API Statistic</a>
                @else
                    <a href="{{ route('front.form') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Isi Form Analisa</a>
{{--                    <a href="{{ route('front.form', ['category' => 2]) }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Isi Form Pertanyaan 2</a>--}}
                    <a href="{{ route('front.history') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Riwayat Analisa Saya</a>

                    <!-- MENU TAMBAHAN (Frontoffice Only - MOBILE) -->
                    <a href="https://product-prompt-generator.primtechdev.com/" target="_blank" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Generator Foto Product</a>
                    <a href="https://social-media-prompt-generator.primtechdev.com/" target="_blank" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Generator Social Media Post</a>
                    <a href="https://veo3-prompt-generator.primtechdev.com/" target="_blank" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Prompt Generator VEO3</a>
                @endif
                <div class="border-t border-gray-700 my-2"></div>
                <div class="py-2 px-2 text-sm">Hi, {{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left py-2 px-2 rounded hover:bg-red-700 hover:text-white transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Login</a>
                <a href="{{ route('register') }}" class="block py-2 px-2 rounded hover:bg-gray-800 hover:text-blue-300">Register</a>
            @endauth
        </div>
    </div>
</nav>
