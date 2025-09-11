<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Generator Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        /* Custom scrollbar for a better look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">

<div class="container mx-auto p-4 md:p-8">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight bg-gradient-to-r from-sky-400 to-indigo-500 text-transparent bg-clip-text">Landing Page Prompt Generator</h1>
        <p class="mt-3 text-lg text-slate-400 max-w-3xl mx-auto">Isi form di bawah ini untuk membuat prompt konten landing page secara otomatis, terinspirasi dari struktur website yang efektif.</p>
    </div>

    <!-- Main Content: Form and Output -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Left Side: Form Input -->
        <div class="bg-slate-800 p-6 rounded-2xl shadow-2xl shadow-slate-950/50 border border-slate-700">
            <h2 class="text-2xl font-semibold mb-6 text-sky-400">Isi Detail Bisnis Anda</h2>
            <form id="prompt-form" class="space-y-8">

                <!-- Bagian 1: Informasi Dasar -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend class="text-xl font-semibold text-white mb-2">Langkah 1: Informasi Dasar</legend>
                    <div>
                        <label for="nama-bisnis" class="block text-sm font-medium text-slate-300 mb-1">Nama Bisnis Anda</label>
                        <input type="text" id="nama-bisnis" placeholder="Contoh: Kaos Sablon Surabaya" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="slogan" class="block text-sm font-medium text-slate-300 mb-1">Slogan / Tagline Singkat</label>
                        <input type="text" id="slogan" placeholder="Contoh: Cepat, Berkualitas, Tanpa Minimal Order" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="target-pasar" class="block text-sm font-medium text-slate-300 mb-1">Target Pasar Anda</label>
                        <input type="text" id="target-pasar" placeholder="Contoh: Komunitas, event organizer, perusahaan" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </fieldset>

                <!-- Bagian 2: Desain & Tampilan -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend class="text-xl font-semibold text-white mb-2">Langkah 2: Desain & Tampilan</legend>
                    <div>
                        <label for="tema" class="block text-sm font-medium text-slate-300 mb-1">Pilih Tema Desain</label>
                        <select id="tema" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                            <option value="Modern & Minimalis">Modern & Minimalis</option>
                            <option value="Profesional & Korporat">Profesional & Korporat</option>
                            <option value="Kreatif & Ceria">Kreatif & Ceria</option>
                            <option value="Elegan & Mewah">Elegan & Mewah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Pilih Skema Warna</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                            <!-- Option 1 -->
                            <label class="flex items-center p-3 bg-slate-700/80 rounded-lg cursor-pointer hover:bg-slate-700 border-2 border-transparent has-[:checked]:border-sky-500 transition-all">
                                <input type="radio" name="warna" value="Biru Profesional" class="form-radio h-4 w-4 text-sky-600 bg-slate-800 border-slate-600 focus:ring-sky-500" checked>
                                <span class="ml-3 text-sm font-medium text-slate-200">Biru Profesional</span>
                                <div class="flex ml-auto -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-sky-500 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-white border-2 border-slate-500"></div>
                                </div>
                            </label>
                            <!-- Option 2 -->
                            <label class="flex items-center p-3 bg-slate-700/80 rounded-lg cursor-pointer hover:bg-slate-700 border-2 border-transparent has-[:checked]:border-sky-500 transition-all">
                                <input type="radio" name="warna" value="Hijau Alam" class="form-radio h-4 w-4 text-sky-600 bg-slate-800 border-slate-600 focus:ring-sky-500">
                                <span class="ml-3 text-sm font-medium text-slate-200">Hijau Alam</span>
                                <div class="flex ml-auto -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-emerald-500 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-white border-2 border-slate-500"></div>
                                </div>
                            </label>
                            <!-- Option 3 -->
                            <label class="flex items-center p-3 bg-slate-700/80 rounded-lg cursor-pointer hover:bg-slate-700 border-2 border-transparent has-[:checked]:border-sky-500 transition-all">
                                <input type="radio" name="warna" value="Elegan Gelap" class="form-radio h-4 w-4 text-sky-600 bg-slate-800 border-slate-600 focus:ring-sky-500">
                                <span class="ml-3 text-sm font-medium text-slate-200">Elegan Gelap</span>
                                <div class="flex ml-auto -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-900 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-violet-500 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-gray-300 border-2 border-slate-500"></div>
                                </div>
                            </label>
                            <!-- Option 4 -->
                            <label class="flex items-center p-3 bg-slate-700/80 rounded-lg cursor-pointer hover:bg-slate-700 border-2 border-transparent has-[:checked]:border-sky-500 transition-all">
                                <input type="radio" name="warna" value="Merah Enerjik" class="form-radio h-4 w-4 text-sky-600 bg-slate-800 border-slate-600 focus:ring-sky-500">
                                <span class="ml-3 text-sm font-medium text-slate-200">Merah Enerjik</span>
                                <div class="flex ml-auto -space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-800 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-red-500 border-2 border-slate-500"></div>
                                    <div class="w-6 h-6 rounded-full bg-white border-2 border-slate-500"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <!-- Bagian 3: Menu Navigasi -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend class="text-xl font-semibold text-white mb-2">Langkah 3: Menu Navigasi Header</legend>
                    <p class="text-sm text-slate-400 -mt-2">Tuliskan nama menu yang akan muncul di bagian atas website. Judul form di bawah akan mengikuti isian ini.</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label for="menu-1" class="block text-sm font-medium text-slate-300 mb-1">Menu 1</label>
                            <input type="text" id="menu-1" value="Home" placeholder="Contoh: Home" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="menu-2" class="block text-sm font-medium text-slate-300 mb-1">Menu 2</label>
                            <input type="text" id="menu-2" value="Layanan" placeholder="Contoh: Layanan" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="menu-3" class="block text-sm font-medium text-slate-300 mb-1">Menu 3</label>
                            <input type="text" id="menu-3" value="Keunggulan" placeholder="Contoh: Keunggulan" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="menu-4" class="block text-sm font-medium text-slate-300 mb-1">Menu 4</label>
                            <input type="text" id="menu-4" value="Cara Pesan" placeholder="Contoh: Cara Pesan" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="menu-5" class="block text-sm font-medium text-slate-300 mb-1">Menu 5</label>
                            <input type="text" id="menu-5" value="Galeri" placeholder="Contoh: Galeri" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="menu-6" class="block text-sm font-medium text-slate-300 mb-1">Menu 6</label>
                            <input type="text" id="menu-6" value="Kontak" placeholder="Contoh: Kontak" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>
                </fieldset>

                <!-- Bagian 4: Konten untuk Menu 1 (Hero) -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend id="legend-menu-1" class="text-xl font-semibold text-white mb-2">Langkah 4: Konten untuk Bagian "Home"</legend>
                    <div>
                        <label for="headline" class="block text-sm font-medium text-slate-300 mb-1">Judul Utama (Headline)</label>
                        <input type="text" id="headline" placeholder="Contoh: Jasa Sablon Kaos Satuan & Lusinan di Surabaya" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="subheadline" class="block text-sm font-medium text-slate-300 mb-1">Sub-Judul (Penjelasan Singkat)</label>
                        <textarea id="subheadline" rows="2" placeholder="Contoh: Wujudkan kaos impianmu dengan kualitas bahan terbaik, bisa pesan satu saja!" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500"></textarea>
                    </div>
                    <div>
                        <label for="cta-utama" class="block text-sm font-medium text-slate-300 mb-1">Teks Tombol Ajakan (Call to Action)</label>
                        <input type="text" id="cta-utama" placeholder="Contoh: Hubungi via WhatsApp" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </fieldset>

                <!-- Bagian 5: Konten untuk Menu 2 (Layanan) -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend id="legend-menu-2" class="text-xl font-semibold text-white mb-2">Langkah 5: Konten untuk Bagian "Layanan"</legend>
                    <div id="layanan-container" class="space-y-4">
                        <!-- Dynamic service items will be injected here -->
                    </div>
                    <button type="button" id="tambah-layanan" class="mt-2 text-sm bg-sky-600 hover:bg-sky-500 text-white font-semibold py-2 px-3 rounded-md transition-colors duration-200">+ Tambah Layanan</button>
                </fieldset>

                <!-- Bagian 6: Konten untuk Menu 3 (Keunggulan) -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend id="legend-menu-3" class="text-xl font-semibold text-white mb-2">Langkah 6: Konten untuk Bagian "Keunggulan"</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="keunggulan-1-judul" class="block text-sm font-medium text-slate-300 mb-1">Keunggulan 1</label>
                            <input type="text" id="keunggulan-1-judul" placeholder="Judul: Kualitas Premium" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm mb-2 focus:ring-sky-500 focus:border-sky-500">
                            <textarea id="keunggulan-1-deskripsi" rows="2" placeholder="Deskripsi singkatnya..." class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500"></textarea>
                        </div>
                        <div>
                            <label for="keunggulan-2-judul" class="block text-sm font-medium text-slate-300 mb-1">Keunggulan 2</label>
                            <input type="text" id="keunggulan-2-judul" placeholder="Judul: Tanpa Minimum Order" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm mb-2 focus:ring-sky-500 focus:border-sky-500">
                            <textarea id="keunggulan-2-deskripsi" rows="2" placeholder="Deskripsi singkatnya..." class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500"></textarea>
                        </div>
                        <div>
                            <label for="keunggulan-3-judul" class="block text-sm font-medium text-slate-300 mb-1">Keunggulan 3</label>
                            <input type="text" id="keunggulan-3-judul" placeholder="Judul: Proses Cepat" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm mb-2 focus:ring-sky-500 focus:border-sky-500">
                            <textarea id="keunggulan-3-deskripsi" rows="2" placeholder="Deskripsi singkatnya..." class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500"></textarea>
                        </div>
                    </div>
                </fieldset>

                <!-- Bagian 7: Konten untuk Menu 4 (Cara Pesan) -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend id="legend-menu-4" class="text-xl font-semibold text-white mb-2">Langkah 7: Konten untuk Bagian "Cara Pesan"</legend>
                    <div>
                        <label for="langkah-1" class="block text-sm font-medium text-slate-300 mb-1">Langkah 1</label>
                        <input type="text" id="langkah-1" placeholder="Contoh: Hubungi CS via WhatsApp" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="langkah-2" class="block text-sm font-medium text-slate-300 mb-1">Langkah 2</label>
                        <input type="text" id="langkah-2" placeholder="Contoh: Kirim Desain & Pilih Ukuran Kaos" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="langkah-3" class="block text-sm font-medium text-slate-300 mb-1">Langkah 3</label>
                        <input type="text" id="langkah-3" placeholder="Contoh: Lakukan Pembayaran" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="langkah-4" class="block text-sm font-medium text-slate-300 mb-1">Langkah 4</label>
                        <input type="text" id="langkah-4" placeholder="Contoh: Pesanan Diproduksi & Dikirim" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </fieldset>

                <!-- Bagian 8: Konten untuk Menu 6 (Kontak) -->
                <fieldset class="space-y-4 border-l-2 border-sky-500 pl-4">
                    <legend id="legend-menu-6" class="text-xl font-semibold text-white mb-2">Langkah 8: Konten untuk Bagian "Kontak"</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-slate-300 mb-1">Nomor WhatsApp</label>
                            <input type="text" id="whatsapp" placeholder="08123456789" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Alamat Email</label>
                            <input type="email" id="email" placeholder="kontak@bisnisanda.com" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        </div>
                    </div>
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-slate-300 mb-1">Alamat Lengkap</label>
                        <input type="text" id="alamat" placeholder="Jl. Pahlawan No. 10, Surabaya" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label for="jam-operasional" class="block text-sm font-medium text-slate-300 mb-1">Jam Operasional</label>
                        <input type="text" id="jam-operasional" placeholder="Senin - Sabtu, 09:00 - 17:00 WIB" class="w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </fieldset>
            </form>
        </div>

        <!-- Right Side: Prompt Output -->
        <div class="sticky top-8 self-start">
            <div class="bg-slate-800 p-6 rounded-2xl shadow-2xl shadow-slate-950/50 border border-slate-700">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-indigo-400">Hasil Prompt Anda</h2>
                    <button id="copy-button" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                        </svg>
                        Salin Prompt
                    </button>
                </div>
                <span id="copy-feedback" class="text-emerald-400 text-sm hidden mb-2">Prompt berhasil disalin!</span>

                <pre class="bg-slate-900/70 p-4 rounded-lg text-slate-300 text-sm whitespace-pre-wrap h-[60vh] lg:h-[75vh] overflow-y-auto"><code id="output-prompt"></code></pre>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('prompt-form');
        const outputPrompt = document.getElementById('output-prompt');
        const copyButton = document.getElementById('copy-button');
        const copyFeedback = document.getElementById('copy-feedback');
        const layananContainer = document.getElementById('layanan-container');
        const tambahLayananButton = document.getElementById('tambah-layanan');

        // Referensi ke elemen legend
        const legendMenu1 = document.getElementById('legend-menu-1');
        const legendMenu2 = document.getElementById('legend-menu-2');
        const legendMenu3 = document.getElementById('legend-menu-3');
        const legendMenu4 = document.getElementById('legend-menu-4');
        const legendMenu6 = document.getElementById('legend-menu-6');

        // Fungsi untuk mengambil nilai dari input
        const getValue = (id) => document.getElementById(id).value.trim();

        const getSelectedColor = () => {
            const checkedRadio = document.querySelector('input[name="warna"]:checked');
            return checkedRadio ? checkedRadio.value : 'Biru Profesional'; // default value
        };

        const addServiceItem = () => {
            const serviceItemCount = layananContainer.getElementsByClassName('layanan-item').length;
            const newServiceDiv = document.createElement('div');
            newServiceDiv.className = 'layanan-item bg-slate-700/50 p-3 rounded-lg space-y-2 relative';

            const layananNumber = serviceItemCount + 1;

            newServiceDiv.innerHTML = `
                    <label class="block text-sm font-medium text-slate-300 mb-1">Layanan ${layananNumber}</label>
                    <input type="text" class="layanan-judul w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500" placeholder="Nama Layanan">
                    <textarea class="layanan-deskripsi w-full bg-slate-700 border-slate-600 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500" rows="2" placeholder="Deskripsi singkat layanan"></textarea>
                    <button type="button" class="hapus-layanan absolute top-2 right-2 text-slate-400 hover:text-red-400 text-2xl font-bold leading-none">&times;</button>
                `;

            layananContainer.appendChild(newServiceDiv);

            newServiceDiv.querySelector('.hapus-layanan').addEventListener('click', () => {
                newServiceDiv.remove();
                generatePrompt();
            });

            generatePrompt();
        };

        tambahLayananButton.addEventListener('click', addServiceItem);


        const generatePrompt = () => {
            // Mengambil semua nilai dari form
            const namaBisnis = getValue('nama-bisnis');
            const slogan = getValue('slogan');
            const targetPasar = getValue('target-pasar');

            const tema = getValue('tema');
            const warna = getSelectedColor();

            const menu1 = getValue('menu-1');
            const menu2 = getValue('menu-2');
            const menu3 = getValue('menu-3');
            const menu4 = getValue('menu-4');
            const menu5 = getValue('menu-5');
            const menu6 = getValue('menu-6');

            // Update judul fieldset secara dinamis
            legendMenu1.textContent = `Langkah 4: Konten untuk Bagian "${menu1 || 'Menu 1'}"`;
            legendMenu2.textContent = `Langkah 5: Konten untuk Bagian "${menu2 || 'Menu 2'}"`;
            legendMenu3.textContent = `Langkah 6: Konten untuk Bagian "${menu3 || 'Menu 3'}"`;
            legendMenu4.textContent = `Langkah 7: Konten untuk Bagian "${menu4 || 'Menu 4'}"`;
            legendMenu6.textContent = `Langkah 8: Konten untuk Bagian "${menu6 || 'Menu 6'}"`;

            const headline = getValue('headline');
            const subheadline = getValue('subheadline');
            const ctaUtama = getValue('cta-utama');

            // Mengambil nilai layanan dinamis
            let layananPromptText = '';
            const layananItems = layananContainer.querySelectorAll('.layanan-item');
            layananItems.forEach((item, index) => {
                const judul = item.querySelector('.layanan-judul').value.trim();
                const deskripsi = item.querySelector('.layanan-deskripsi').value.trim();
                if (judul) {
                    layananPromptText += `   - **Layanan ${index + 1}:**\n`;
                    layananPromptText += `     - Judul: "${judul}"\n`;
                    layananPromptText += `     - Deskripsi: "${deskripsi || 'Deskripsi layanan belum diisi.'}"\n`;
                }
            });

            if (layananItems.length === 0) {
                layananPromptText = '   - [Belum ada layanan yang ditambahkan]\n';
            }

            const keunggulan1Judul = getValue('keunggulan-1-judul');
            const keunggulan1Deskripsi = getValue('keunggulan-1-deskripsi');
            const keunggulan2Judul = getValue('keunggulan-2-judul');
            const keunggulan2Deskripsi = getValue('keunggulan-2-deskripsi');
            const keunggulan3Judul = getValue('keunggulan-3-judul');
            const keunggulan3Deskripsi = getValue('keunggulan-3-deskripsi');

            const langkah1 = getValue('langkah-1');
            const langkah2 = getValue('langkah-2');
            const langkah3 = getValue('langkah-3');
            const langkah4 = getValue('langkah-4');

            const whatsapp = getValue('whatsapp');
            const email = getValue('email');
            const alamat = getValue('alamat');
            const jamOperasional = getValue('jam-operasional');

            // Membuat template prompt
            let promptText = 'Anda adalah seorang expert web developer yang sangat mahir membuat landing page modern menggunakan HTML dan Tailwind CSS.\n\n';
            promptText += 'TUGAS ANDA:\n';
            promptText += 'Buat satu file HTML LENGKAP untuk landing page berdasarkan detail bisnis di bawah ini. Seluruh kode (HTML, styling dengan Tailwind classes) harus berada dalam SATU FILE.\n\n';
            promptText += 'PERSYARATAN TEKNIS:\n';
            promptText += '1.  **Framework:** Gunakan Tailwind CSS. Sertakan CDN script di dalam tag <head>: <script src="https://cdn.tailwindcss.com"><\/script>.\n';
            promptText += `2.  **Desain & Warna:** Terapkan gaya desain **${tema}**. Gunakan skema warna **${warna}** sebagai palet utama (untuk background, teks, tombol, dan aksen).\n`;
            promptText += '3.  **Responsif:** Pastikan halaman sepenuhnya responsif untuk desktop, tablet, dan mobile.\n';
            promptText += '4.  **Struktur:** Gunakan tag HTML semantik (header, section, footer, dll).\n';
            promptText += '5.  **Navigasi:** Pastikan link menu di header mengarah ke ID section yang sesuai.\n';
            promptText += '6.  **Gambar & Ikon:** Gunakan placeholder gambar dari `https://placehold.co` untuk semua gambar dummy. Untuk ikon, gunakan inline SVG dari library seperti Heroicons, pastikan ikon sesuai dengan konteksnya.\n\n';

            promptText += '---\n\n';
            promptText += '### DETAIL BISNIS UNTUK KONTEN WEBSITE\n\n';
            promptText += `- **Nama Bisnis:** ${namaBisnis || '[Nama Bisnis Belum Diisi]'}\n`;
            promptText += `- **Slogan/Tagline:** ${slogan || '[Slogan Belum Diisi]'}\n`;
            promptText += `- **Target Pasar:** ${targetPasar || '[Target Pasar Belum Diisi]'}\n\n`;
            promptText += '---\n\n';
            promptText += '### STRUKTUR DAN KONTEN LANDING PAGE\n\n';

            promptText += '**1. Header & Navigasi:**\n';
            promptText += '   - Buat header yang `sticky` (menempel di atas saat di-scroll) dengan background blur untuk efek modern.\n';
            promptText += `   - Di sebelah kiri, tampilkan **Nama Bisnis:** "${namaBisnis || 'Nama Bisnis'}".\n`;
            promptText += '   - Di sebelah kanan, buat daftar menu navigasi. Menu ini harus bisa menjadi menu hamburger di versi mobile.\n';
            promptText += '   - **Daftar Menu** (buat sebagai link anchor):\n';
            if (menu1) promptText += `     - **${menu1}** (arahkan ke \`href="#home"\`)\n`;
            if (menu2) promptText += `     - **${menu2}** (arahkan ke \`href="#layanan"\`)\n`;
            if (menu3) promptText += `     - **${menu3}** (arahkan ke \`href="#keunggulan"\`)\n`;
            if (menu4) promptText += `     - **${menu4}** (arahkan ke \`href="#cara-pesan"\`)\n`;
            if (menu5) promptText += `     - **${menu5}** (arahkan ke \`href="#galeri"\`)\n`;
            if (menu6) promptText += `     - **${menu6}** (arahkan ke \`href="#kontak"\`)\n\n`;

            promptText += `**2. Bagian "${menu1 || 'Home'}" (Hero Section):**\n`;
            promptText += '   - Beri section ini `id="home"`.\n';
            promptText += '   - **Layout:** Buat layout 2 kolom: teks di kiri, gambar di kanan.\n';
            promptText += `   - **Judul Utama (H1):** "${headline || 'Headline Utama Belum Diisi'}"\n`;
            promptText += `   - **Sub-Judul (paragraf di bawah H1):** "${subheadline || 'Sub-headline Belum Diisi'}"\n`;
            promptText += `   - **Tombol Primary Call-to-Action:** Buat tombol yang menonjol dengan teks "${ctaUtama || 'Teks Tombol CTA'}"\n`;
            promptText += '   - **Gambar Utama:** Gunakan gambar dummy `https://placehold.co/600x400?text=Produk+Anda`.\n\n';

            promptText += `**3. Bagian "${menu2 || 'Layanan'}" (Services Section):**\n`;
            promptText += '   - Beri section ini `id="layanan"`.\n';
            promptText += '   - Buat layout grid card untuk menampilkan daftar layanan. Setiap card WAJIB memiliki ikon SVG yang relevan.\n';
            promptText += layananPromptText + '\n';

            promptText += `**4. Bagian "${menu3 || 'Keunggulan'}" (Features Section):**\n`;
            promptText += '   - Beri section ini `id="keunggulan"`.\n';
            promptText += '   - Buat section dengan 3 kolom untuk menampilkan keunggulan. Setiap poin WAJIB memiliki ikon SVG yang relevan di atas judulnya.\n';
            promptText += '   - **Keunggulan 1:**\n';
            promptText += `     - Judul: "${keunggulan1Judul || 'Judul Keunggulan 1'}"\n`;
            promptText += `     - Deskripsi: "${keunggulan1Deskripsi || 'Deskripsi singkat keunggulan 1.'}"\n`;
            promptText += '   - **Keunggulan 2:**\n';
            promptText += `     - Judul: "${keunggulan2Judul || 'Judul Keunggulan 2'}"\n`;
            promptText += `     - Deskripsi: "${keunggulan2Deskripsi || 'Deskripsi singkat keunggulan 2.'}"\n`;
            promptText += '   - **Keunggulan 3:**\n';
            promptText += `     - Judul: "${keunggulan3Judul || 'Judul Keunggulan 3'}"\n`;
            promptText += `     - Deskripsi: "${keunggulan3Deskripsi || 'Deskripsi singkat keunggulan 3.'}"\n\n`;

            promptText += `**5. Bagian "${menu4 || 'Cara Pesan'}" (How It Works Section):**\n`;
            promptText += '   - Beri section ini `id="cara-pesan"`.\n';
            promptText += '   - Buat section yang menjelaskan proses dalam 4 langkah mudah. Gunakan visualisasi seperti angka besar, garis penghubung, atau ikon SVG yang relevan untuk setiap langkah.\n';
            promptText += `   - **Langkah 1:** "${langkah1 || 'Deskripsi Langkah 1'}"\n`;
            promptText += `   - **Langkah 2:** "${langkah2 || 'Deskripsi Langkah 2'}"\n`;
            promptText += `   - **Langkah 3:** "${langkah3 || 'Deskripsi Langkah 3'}"\n`;
            promptText += `   - **Langkah 4:** "${langkah4 || 'Deskripsi Langkah 4'}"\n\n`;

            promptText += `**6. Bagian "${menu5 || 'Galeri'}" (Gallery Section):**\n`;
            promptText += '   - Beri section ini `id="galeri"`.\n';
            promptText += '   - Buat galeri gambar dengan layout grid (3 kolom di desktop). Tampilkan 6 gambar dummy dari `https://placehold.co/400x300?text=Hasil+Kerja` (ubah angka di akhir untuk setiap gambar).\n\n';

            promptText += '**7. Final Call-to-Action Section:**\n';
            promptText += '   - Sebelum footer, buat satu section terakhir yang mengajak pengunjung untuk bertindak. Gunakan judul yang kuat dan tombol CTA yang sama seperti di Hero Section.\n\n';

            promptText += `**8. Bagian "${menu6 || 'Kontak'}" (Footer Section):**\n`;
            promptText += '   - Beri section ini `id="kontak"`.\n';
            promptText += '   - Buat footer yang rapi berisi semua informasi kontak.\n';
            promptText += `   - **Nomor WhatsApp:** ${whatsapp || '[Belum Diisi]'}\n`;
            promptText += `   - **Email:** ${email || '[Belum Diisi]'}\n`;
            promptText += `   - **Alamat:** ${alamat || '[Belum Diisi]'}\n`;
            promptText += `   - **Jam Operasional:** ${jamOperasional || '[Belum Diisi]'}\n\n`;

            promptText += '---\n\n';
            promptText += 'Sekarang, hasilkan kode HTML lengkapnya. Pastikan kode tersebut adalah satu file utuh yang valid dan siap pakai.';


            outputPrompt.textContent = promptText;
        };

        // Generate prompt secara real-time saat ada input
        form.addEventListener('input', generatePrompt);

        // Copy button functionality
        copyButton.addEventListener('click', () => {
            const textToCopy = outputPrompt.textContent;
            const tempTextArea = document.createElement('textarea');
            tempTextArea.value = textToCopy;
            document.body.appendChild(tempTextArea);
            tempTextArea.select();
            try {
                document.execCommand('copy');
                copyFeedback.classList.remove('hidden');
                setTimeout(() => {
                    copyFeedback.classList.add('hidden');
                }, 2000);
            } catch (err) {
                console.error('Gagal menyalin teks: ', err);
            }
            document.body.removeChild(tempTextArea);
        });

        // Panggil sekali saat halaman dimuat untuk menampilkan template awal dan item layanan pertama
        addServiceItem();
        generatePrompt();
    });
</script>
</body>
</html>

