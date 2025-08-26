<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generator Prompt Media Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lobster&family=Lora&family=Oswald&family=Playfair+Display&family=Roboto&family=Poppins&family=Montserrat&family=Merriweather&family=Pacifico&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .animate-pulse-once {
            animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1);
        }
        /* Styling for font options */
        .font-select {
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-3xl bg-white dark:bg-gray-800 shadow-2xl rounded-2xl p-6 md:p-10 space-y-6">
    <div class="text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">Generator Prompt Media Sosial</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Isi form di bawah untuk membuat prompt deskriptif bagi AI generator gambar.</p>
    </div>

    <!-- Form Input -->
    <div class="space-y-6">
        <!-- 1. Isi Campaign -->
        <div>
            <label for="isiCampaign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">1. Isi Campaign / Brief</label>
            <textarea id="isiCampaign" name="isiCampaign" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5" placeholder="Contoh: Diskon spesial 50% untuk produk kopi terbaru 'Java Blend' selama bulan Juli!"></textarea>
        </div>

        <!-- 2. Jenis Font Utama -->
        <div>
            <label for="jenisFont" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">2. Jenis Font Utama</label>
            <select id="jenisFont" name="jenisFont" class="font-select mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5">
                <option value="Sans-serif Modern (seperti Roboto)" style="font-family: 'Roboto', sans-serif; font-size: 1.1rem;">Roboto (Modern & Jelas)</option>
                <option value="Sans-serif Geometris (seperti Poppins)" style="font-family: 'Poppins', sans-serif; font-size: 1.1rem;">Poppins (Ramah & Profesional)</option>
                <option value="Sans-serif Versatil (seperti Montserrat)" style="font-family: 'Montserrat', sans-serif; font-size: 1.1rem;">Montserrat (Stylish & Urban)</option>
                <option value="Serif Elegan (seperti Lora)" style="font-family: 'Lora', serif; font-size: 1.1rem;">Lora (Elegan & Klasik)</option>
                <option value="Serif Tradisional (seperti Merriweather)" style="font-family: 'Merriweather', serif; font-size: 1.1rem;">Merriweather (Mudah Dibaca & Formal)</option>
                <option value="Serif Display (seperti Playfair Display)" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">Playfair Display (Mewah & Artistik)</option>
                <option value="Script Playful (seperti Lobster)" style="font-family: 'Lobster', cursive; font-size: 1.2rem;">Lobster (Playful & Kasual)</option>
                <option value="Script Handwriting (seperti Pacifico)" style="font-family: 'Pacifico', cursive; font-size: 1.2rem;">Pacifico (Santai & Personal)</option>
                <option value="Sans-serif Tebal (seperti Oswald)" style="font-family: 'Oswald', sans-serif; font-size: 1.1rem;">Oswald (Tebal & Tegas)</option>
                <option value="Display Headline (seperti Bebas Neue)" style="font-family: 'Bebas Neue', cursive; font-size: 1.2rem;">Bebas Neue (Judul & Impactful)</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 3. Desain Campaign -->
            <div>
                <label for="desainCampaign" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">3. Gaya Desain Campaign</label>
                <select id="desainCampaign" name="desainCampaign" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5">
                    <option>Minimalis & Modern</option>
                    <option>Fun & Colorful</option>
                    <option>Elegan & Mewah</option>
                    <option>Retro & Vintage</option>
                    <option>Futuristik & Teknologi</option>
                    <option>Natural & Organik</option>
                </select>
            </div>

            <!-- 4. Rasio Ukuran Gambar -->
            <div>
                <label for="rasioGambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">4. Rasio Ukuran Gambar</label>
                <select id="rasioGambar" name="rasioGambar" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5">
                    <option value="1:1 (persegi)">1:1 (Instagram Post, Facebook Post)</option>
                    <option value="9:16 (vertikal)">9:16 (Instagram Story, Reels, TikTok, YT Shorts)</option>
                    <option value="4:5 (potret)">4:5 (Instagram Portrait Post)</option>
                    <option value="16:9 (lanskap)">16:9 (YouTube Thumbnail, Twitter Post)</option>
                </select>
            </div>
        </div>

        <!-- 5. CTA -->
        <div>
            <label for="cta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">5. Call to Action (CTA)</label>
            <input type="text" id="cta" name="cta" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5" placeholder="Contoh: Beli Sekarang, Swipe Up, Info Lebih Lanjut">
        </div>

        <!-- 6. Jenis Font untuk CTA -->
        <div>
            <label for="jenisFontCta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">6. Jenis Font untuk CTA</label>
            <select id="jenisFontCta" name="jenisFontCta" class="font-select mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5">
                <option value="Sans-serif Modern (seperti Roboto)" style="font-family: 'Roboto', sans-serif; font-size: 1.1rem;">Roboto (Modern & Jelas)</option>
                <option value="Sans-serif Geometris (seperti Poppins)" style="font-family: 'Poppins', sans-serif; font-size: 1.1rem;">Poppins (Ramah & Profesional)</option>
                <option value="Sans-serif Versatil (seperti Montserrat)" style="font-family: 'Montserrat', sans-serif; font-size: 1.1rem;">Montserrat (Stylish & Urban)</option>
                <option value="Serif Elegan (seperti Lora)" style="font-family: 'Lora', serif; font-size: 1.1rem;">Lora (Elegan & Klasik)</option>
                <option value="Serif Tradisional (seperti Merriweather)" style="font-family: 'Merriweather', serif; font-size: 1.1rem;">Merriweather (Mudah Dibaca & Formal)</option>
                <option value="Serif Display (seperti Playfair Display)" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">Playfair Display (Mewah & Artistik)</option>
                <option value="Script Playful (seperti Lobster)" style="font-family: 'Lobster', cursive; font-size: 1.2rem;">Lobster (Playful & Kasual)</option>
                <option value="Script Handwriting (seperti Pacifico)" style="font-family: 'Pacifico', cursive; font-size: 1.2rem;">Pacifico (Santai & Personal)</option>
                <option value="Sans-serif Tebal (seperti Oswald)" style="font-family: 'Oswald', sans-serif; font-size: 1.1rem;">Oswald (Tebal & Tegas)</option>
                <option value="Display Headline (seperti Bebas Neue)" style="font-family: 'Bebas Neue', cursive; font-size: 1.2rem;">Bebas Neue (Judul & Impactful)</option>
            </select>
        </div>

        <!-- 7. Warna Background -->
        <div>
            <label for="warnaBackground" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">7. Preferensi Warna Background</label>
            <input type="text" id="warnaBackground" name="warnaBackground" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5" placeholder="Contoh: Biru laut, gradasi oranye ke pink, pastel lembut">
        </div>

        <!-- 8. Detil Tambahan -->
        <div>
            <label for="detilTambahan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">8. Detail Tambahan</label>
            <textarea id="detilTambahan" name="detilTambahan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 dark:bg-gray-700 p-2.5" placeholder="Contoh: Tambahkan elemen daun tropis, jangan ada gambar orang."></textarea>
        </div>

        <!-- Catatan Tambahan -->
        <div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-bold">NB:</span> Upload foto yang akan diproses terlebih dahulu di chat GPT sebelum memasukan prompt.
            </p>
        </div>
    </div>

    <!-- Tombol Generate -->
    <div class="pt-4 text-center">
        <button id="generateBtn" class="w-full md:w-auto inline-flex justify-center items-center px-8 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-transform transform hover:scale-105">
            Buat Prompt
        </button>
    </div>

    <!-- Hasil Prompt -->
    <div id="hasilContainer" class="hidden pt-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">✅ Prompt Berhasil Dibuat!</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Salin prompt di bawah ini dan gunakan di ChatGPT, Midjourney, atau AI generator gambar lainnya.</p>
        <div class="relative bg-gray-100 dark:bg-gray-900 rounded-lg p-4">
            <pre id="hasilPrompt" class="text-sm whitespace-pre-wrap break-words font-mono text-gray-800 dark:text-gray-200"></pre>
            <button id="copyBtn" class="absolute top-2 right-2 p-2 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900" title="Salin ke clipboard">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </button>
        </div>
    </div>

</div>

<script>
    // Ambil elemen dari DOM
    const generateBtn = document.getElementById('generateBtn');
    const copyBtn = document.getElementById('copyBtn');
    const hasilContainer = document.getElementById('hasilContainer');
    const hasilPrompt = document.getElementById('hasilPrompt');

    // Event listener untuk tombol generate
    generateBtn.addEventListener('click', () => {
        // Ambil semua nilai dari form
        const isiCampaign = document.getElementById('isiCampaign').value.trim();
        const jenisFont = document.getElementById('jenisFont').value;
        const desainCampaign = document.getElementById('desainCampaign').value;
        const rasioGambar = document.getElementById('rasioGambar').value;
        const cta = document.getElementById('cta').value.trim();
        const jenisFontCta = document.getElementById('jenisFontCta').value;
        const warnaBackground = document.getElementById('warnaBackground').value.trim();
        const detilTambahan = document.getElementById('detilTambahan').value.trim();

        // Validasi input utama
        if (!isiCampaign) {
            alert('Harap isi bagian "Isi Campaign / Brief" terlebih dahulu.');
            return;
        }

        // Mulai membangun prompt
        let prompt = `Buat sebuah gambar desain poster promosi untuk media sosial dengan kualitas tinggi, photorealistic, dan sangat detail. Jadikan gambar yang sebelumnya diunggah sebagai referensi utama untuk produk yang ditampilkan. Berikut adalah arahan lengkapnya:\n\n`;

        prompt += `**1. Tema & Subjek Utama:**\n`;
        prompt += `- Fokus utama adalah promosi kampanye dengan pesan: "${isiCampaign}".\n`;
        prompt += `- Gunakan produk dari gambar yang telah diunggah sebelumnya sebagai subjek utama. Posisikan produk ini sebagai pusat perhatian dengan pencahayaan studio yang dramatis dan profesional.\n`;

        prompt += `\n**2. Gaya & Suasana Visual:**\n`;
        prompt += `- Gaya visual yang diinginkan adalah **${desainCampaign}**.\n`;
        prompt += `- Suasana (mood) harus sesuai dengan gaya tersebut (misalnya, jika elegan, gunakan warna mewah; jika fun, gunakan warna cerah).\n`;

        prompt += `\n**3. Tipografi:**\n`;
        prompt += `- Untuk teks utama (headline/campaign), gunakan gaya tipografi yang sesuai dengan deskripsi: **${jenisFont}**.\n`;
        if (cta) {
            prompt += `- Untuk teks Call-to-Action (CTA), gunakan gaya tipografi: **${jenisFontCta}**.\n`;
        }
        prompt += `- Pastikan semua teks mudah dibaca, dengan hierarki visual yang jelas.\n`;

        prompt += `\n**4. Komposisi & Tata Letak:**\n`;
        prompt += `- Rasio aspek gambar harus **${rasioGambar}**.\n`;
        if (cta) {
            prompt += `- Sertakan teks CTA "${cta}" secara jelas. Posisikan di tempat yang strategis tanpa menutupi produk.\n`;
        }
        prompt += `- Terapkan prinsip desain seperti rule of thirds untuk komposisi yang seimbang dan menarik secara visual.\n`;

        prompt += `\n**5. Warna & Pencahayaan:**\n`;
        if (warnaBackground) {
            prompt += `- Palet warna dominan harus berpusat pada **${warnaBackground}** sebagai warna background atau elemen utama. Pastikan teks tetap kontras dan mudah terbaca.\n`;
        } else {
            prompt += `- Gunakan palet warna yang harmonis dan sesuai dengan gaya ${desainCampaign}.\n`;
        }
        prompt += `- Pencahayaan harus profesional, menonjolkan detail produk dan menciptakan kedalaman.\n`;

        prompt += `\n**6. Detail Tambahan & Larangan:**\n`;
        if (detilTambahan) {
            prompt += `- Perhatikan detail tambahan berikut: ${detilTambahan}\n`;
        } else {
            prompt += `- Pastikan tidak ada teks placeholder atau watermark. Semua elemen harus terlihat final dan profesional.\n`;
        }

        prompt += `\n**Parameter Akhir (untuk AI):**\n`;
        prompt += `--ar ${rasioGambar.split(' ')[0]} --style raw --quality high --v 6.0`;

        // Tampilkan hasil
        hasilPrompt.textContent = prompt;
        hasilContainer.classList.remove('hidden');
        hasilContainer.classList.add('animate-pulse-once');

        // Hapus animasi setelah selesai agar tidak berulang
        setTimeout(() => {
            hasilContainer.classList.remove('animate-pulse-once');
        }, 1000);

        // Scroll ke hasil
        hasilContainer.scrollIntoView({ behavior: 'smooth' });
    });

    // Event listener untuk tombol copy
    copyBtn.addEventListener('click', () => {
        const textToCopy = hasilPrompt.textContent;

        // Gunakan document.execCommand sebagai fallback
        const textArea = document.createElement('textarea');
        textArea.value = textToCopy;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            copyBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                `;
            copyBtn.title = "Tersalin!";
        } catch (err) {
            console.error('Gagal menyalin teks: ', err);
            alert('Gagal menyalin teks.');
        }
        document.body.removeChild(textArea);

        // Kembalikan ikon tombol setelah 2 detik
        setTimeout(() => {
            copyBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                `;
            copyBtn.title = "Salin ke clipboard";
        }, 2000);
    });

</script>
</body>
</html>
