<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veo 3 Prompt Generator with Gemini AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .input-group label {
            transition: all 0.3s ease;
        }
        .input-field {
            background-color: #1f2937; /* bg-gray-800 */
            border-color: #4b5563; /* border-gray-600 */
            color: #f3f4f6; /* text-gray-100 */
        }
        .input-field:focus {
            outline: none;
            border-color: #4f46e5; /* border-indigo-600 */
            box-shadow: 0 0 0 2px #4f46e5;
        }
        .btn-copy {
            transition: background-color 0.2s ease-in-out;
        }
        .copied-feedback {
            position: absolute;
            top: -25px;
            right: 0;
            background-color: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            pointer-events: none;
        }
        .copied-feedback.show {
            opacity: 1;
            transform: translateY(0);
        }
        .gemini-btn {
            background-color: #374151; /* bg-gray-700 */
            color: #d1d5db; /* text-gray-300 */
            transition: all 0.2s ease-in-out;
        }
        .gemini-btn:hover {
            background-color: #4b5563; /* bg-gray-600 */
            color: white;
        }
        .gemini-btn:disabled {
            background-color: #4b5563; /* bg-gray-600 */
            cursor: not-allowed;
            opacity: 0.7;
        }
        #notification {
            transition: transform 0.5s ease-in-out;
        }
        /* Styling for the voice details section */
        #voice-details-section {
            transition: opacity 0.5s ease, max-height 0.5s ease, padding 0.5s ease, margin 0.5s ease;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding-top: 0;
            margin-top: 0;
        }
        #voice-details-section.show {
            max-height: 1000px; /* Adjust as needed */
            opacity: 1;
            padding-top: 1.5rem; /* pt-6 */
            margin-top: 1.5rem; /* mt-6 */
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-4 sm:p-6 lg:p-8">
<div class="max-w-7xl mx-auto">
    <header class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-600">Veo 3 Prompt Generator ✨</h1>
        <p class="mt-2 text-gray-400">Didukung oleh Gemini untuk ide-ide kreatif tanpa batas.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Kolom Input -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
            <h2 class="text-2xl font-semibold mb-6 border-b border-gray-600 pb-3">Komponen Prompt</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- 1. Subjek -->
                <div class="input-group">
                    <label for="subject" class="block text-sm font-medium text-gray-300 mb-1">1. Subjek</label>
                    <input type="text" id="subject" class="input-field w-full rounded-md p-2" placeholder="Contoh: seekor kucing oranye">
                </div>
                <!-- 2. Aksi -->
                <div class="input-group">
                    <label for="action" class="block text-sm font-medium text-gray-300 mb-1">2. Aksi</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="action" class="input-field w-full rounded-md p-2" placeholder="Contoh: bermain dengan benang wol">
                        <button id="gemini-action" class="gemini-btn p-2 rounded-md" title="✨ Gali Ide Aksi dengan Gemini">✨</button>
                    </div>
                </div>
                <!-- 3. Ekspresi -->
                <div class="input-group">
                    <label for="expression" class="block text-sm font-medium text-gray-300 mb-1">3. Ekspresi</label>
                    <input type="text" id="expression" class="input-field w-full rounded-md p-2" placeholder="Contoh: gembira dan penasaran">
                </div>
                <!-- 4. Tempat -->
                <div class="input-group">
                    <label for="place" class="block text-sm font-medium text-gray-300 mb-1">4. Tempat</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="place" class="input-field w-full rounded-md p-2" placeholder="Contoh: di ruang tamu yang nyaman">
                        <button id="gemini-place" class="gemini-btn p-2 rounded-md" title="✨ Gali Ide Tempat dengan Gemini">✨</button>
                    </div>
                </div>
                <!-- 5. Waktu -->
                <div class="input-group">
                    <label for="time" class="block text-sm font-medium text-gray-300 mb-1">5. Waktu</label>
                    <select id="time" class="input-field w-full rounded-md p-2">
                        <option value="golden hour">Golden Hour</option>
                        <option value="blue hour">Blue Hour</option>
                        <option value="daylight">Siang Hari (Daylight)</option>
                        <option value="night">Malam Hari (Night)</option>
                        <option value="dawn">Fajar (Dawn)</option>
                        <option value="dusk">Senja (Dusk)</option>
                    </select>
                </div>
                <!-- 6. Gerakan Kamera -->
                <div class="input-group">
                    <label for="camera" class="block text-sm font-medium text-gray-300 mb-1">6. Gerakan Kamera</label>
                    <select id="camera" class="input-field w-full rounded-md p-2">
                        <option value="static">Static (Statis)</option>
                        <option value="pan left">Pan Left (Geser Kiri)</option>
                        <option value="pan right">Pan Right (Geser Kanan)</option>
                        <option value="tilt up">Tilt Up (Miring ke Atas)</option>
                        <option value="tilt down">Tilt Down (Miring ke Bawah)</option>
                        <option value="zoom in">Zoom In (Perbesar)</option>
                        <option value="zoom out">Zoom Out (Perkecil)</option>
                        <option value="dolly in">Dolly In (Dolly Masuk)</option>
                        <option value="dolly out">Dolly Out (Dolly Keluar)</option>
                        <option value="crane up">Crane Up (Crane ke Atas)</option>
                        <option value="crane down">Crane Down (Crane ke Bawah)</option>
                        <option value="tracking shot">Tracking Shot (Tembakan Pelacakan)</option>
                        <option value="handheld">Handheld (Genggam)</option>
                        <option value="drone shot">Drone Shot (Tembakan Drone)</option>
                        <option value="3D rotation">3D Rotation (Rotasi 3D)</option>
                        <option value="vortex in">Vortex In (Pusaran Masuk)</option>
                        <option value="vortex out">Vortex Out (Pusaran Keluar)</option>
                        <option value="rise">Rise (Naik)</option>
                        <option value="fall">Fall (Jatuh)</option>
                        <option value="twist clockwise">Twist Clockwise (Putar Searah Jarum Jam)</option>
                        <option value="twist counter-clockwise">Twist Counter-Clockwise (Putar Berlawanan Arah Jam)</option>
                        <option value="horizontal wave">Horizontal Wave (Gelombang Horizontal)</option>
                        <option value="vertical wave">Vertical Wave (Gelombang Vertikal)</option>
                        <option value="roll clockwise">Roll Clockwise (Bergulir Searah Jarum Jam)</option>
                        <option value="roll counter-clockwise">Roll Counter-Clockwise (Bergulir Berlawanan Arah Jam)</option>
                        <option value="wobble">Wobble (Goyangan)</option>
                        <option value="pulse">Pulse (Denyut)</option>
                        <option value="flicker">Flicker (Kerlip)</option>
                        <option value="jitter">Jitter (Getaran)</option>
                        <option value="shake">Shake (Guncangan)</option>
                        <option value="boomerang">Boomerang (Bumerang)</option>
                    </select>
                </div>
                <!-- 7. Pencahayaan -->
                <div class="input-group">
                    <label for="lighting" class="block text-sm font-medium text-gray-300 mb-1">7. Pencahayaan</label>
                    <select id="lighting" class="input-field w-full rounded-md p-2">
                        <option value="cinematic lighting">Cinematic (Sinematik)</option>
                        <option value="dramatic lighting">Dramatic (Dramatis)</option>
                        <option value="soft light">Soft Light (Cahaya Lembut)</option>
                        <option value="hard light">Hard Light (Cahaya Keras)</option>
                        <option value="natural light">Natural Light (Cahaya Alami)</option>
                        <option value="neon lighting">Neon</option>
                        <option value="silhouette">Silhouette (Siluet)</option>
                        <option value="high key">High Key</option>
                        <option value="low key">Low Key</option>
                    </select>
                </div>
                <!-- 8. Gaya Video -->
                <div class="input-group">
                    <label for="style" class="block text-sm font-medium text-gray-300 mb-1">8. Gaya Video</label>
                    <select id="style" class="input-field w-full rounded-md p-2">
                        <option value="realistic">Realistic (Realistis)</option>
                        <option value="cinematic">Cinematic (Sinematik)</option>
                        <option value="animation">Animation (Animasi)</option>
                        <option value="hyperlapse">Hyperlapse</option>
                        <option value="timelapse">Timelapse</option>
                        <option value="slow motion">Slow Motion</option>
                        <option value="documentary">Documentary (Dokumenter)</option>
                        <option value="archival footage">Archival Footage (Rekaman Arsip)</option>
                        <option value="CGI">CGI</option>
                        <option value="8-bit">8-bit</option>
                    </select>
                </div>
                <!-- 9. Suasana Video -->
                <div class="input-group sm:col-span-2">
                    <label for="mood" class="block text-sm font-medium text-gray-300 mb-1">9. Suasana Video</label>
                    <select id="mood" class="input-field w-full rounded-md p-2">
                        <option value="cheerful">Cheerful (Ceria)</option>
                        <option value="sad">Sad (Sedih)</option>
                        <option value="mysterious">Mysterious (Misterius)</option>
                        <option value="tense">Tense (Menegangkan)</option>
                        <option value="romantic">Romantic (Romantis)</option>
                        <option value="epic">Epic (Epik)</option>
                        <option value="funny">Funny (Lucu)</option>
                        <option value="peaceful">Peaceful (Damai)</option>
                        <option value="nostalgic">Nostalgic (Nostalgia)</option>
                    </select>
                </div>
                <!-- 10. Suara atau Musik -->
                <div class="input-group sm:col-span-2">
                    <label for="sound" class="block text-sm font-medium text-gray-300 mb-1">10. Suara atau Musik</label>
                    <input type="text" id="sound" class="input-field w-full rounded-md p-2" placeholder="Contoh: musik lofi yang menenangkan, suara dengkuran kucing">
                </div>
                <!-- 11. Kalimat yang diucapkan -->
                <div class="input-group sm:col-span-2">
                    <label for="dialogue" class="block text-sm font-medium text-gray-300 mb-1">11. Kalimat yang diucapkan (opsional)</label>
                    <input type="text" id="dialogue" class="input-field w-full rounded-md p-2" placeholder="Isi untuk menampilkan detail suara...">
                </div>

                <!-- 12. Detail Suara (Konteksual) -->
                <div id="voice-details-section" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-gray-700">
                    <h3 class="sm:col-span-2 text-lg font-semibold text-gray-200 -mt-2 mb-2">12. Detail Suara Pembicara</h3>
                    <div class="input-group">
                        <label for="voice-age-gender" class="block text-sm font-medium text-gray-300 mb-1">1. Jenis Kelamin & Usia</label>
                        <input type="text" id="voice-age-gender" class="input-field w-full rounded-md p-2" placeholder="Contoh: Wanita muda, kakek-kakek">
                    </div>
                    <div class="input-group">
                        <label for="voice-accent" class="block text-sm font-medium text-gray-300 mb-1">2. Aksen atau Dialek</label>
                        <input type="text" id="voice-accent" class="input-field w-full rounded-md p-2" placeholder="Contoh: Surabaya, British accent">
                    </div>
                    <div class="input-group">
                        <label for="voice-pitch" class="block text-sm font-medium text-gray-300 mb-1">3. Nada atau Pitch Suara</label>
                        <select id="voice-pitch" class="input-field w-full rounded-md p-2">
                            <option value="">Pilih nada...</option>
                            <option value="tinggi">Tinggi (High-pitched)</option>
                            <option value="sedang">Sedang (Medium-pitched)</option>
                            <option value="rendah">Rendah (Low-pitched)</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="voice-speed" class="block text-sm font-medium text-gray-300 mb-1">4. Kecepatan Bicara</label>
                        <select id="voice-speed" class="input-field w-full rounded-md p-2">
                            <option value="">Pilih kecepatan...</option>
                            <option value="cepat">Cepat (Fast-paced)</option>
                            <option value="sedang">Sedang (Normal-paced)</option>
                            <option value="lambat">Lambat (Slow-paced)</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="voice-emotion" class="block text-sm font-medium text-gray-300 mb-1">5. Ekspresi Emosi</label>
                        <input type="text" id="voice-emotion" class="input-field w-full rounded-md p-2" placeholder="Contoh: Ceria, marah, santai">
                    </div>
                    <div class="input-group">
                        <label for="voice-style" class="block text-sm font-medium text-gray-300 mb-1">6. Gaya atau Karakter Suara</label>
                        <input type="text" id="voice-style" class="input-field w-full rounded-md p-2" placeholder="Contoh: Formal, storytelling, narator">
                    </div>
                    <div class="input-group">
                        <label for="voice-intonation" class="block text-sm font-medium text-gray-300 mb-1">7. Intonasi dan Penekanan</label>
                        <input type="text" id="voice-intonation" class="input-field w-full rounded-md p-2" placeholder="Contoh: Dramatis, menenangkan, datar">
                    </div>
                    <div class="input-group">
                        <label for="voice-context" class="block text-sm font-medium text-gray-300 mb-1">8. Konteks Situasi</label>
                        <input type="text" id="voice-context" class="input-field w-full rounded-md p-2" placeholder="Contoh: Iklan, audiobook, berita">
                    </div>
                    <div class="input-group">
                        <label for="voice-language" class="block text-sm font-medium text-gray-300 mb-1">9. Bahasa yang Digunakan</label>
                        <input type="text" id="voice-language" class="input-field w-full rounded-md p-2" placeholder="Contoh: Bahasa Indonesia, English">
                    </div>
                    <div class="input-group">
                        <label for="voice-duration" class="block text-sm font-medium text-gray-300 mb-1">10. Panjang atau Durasi</label>
                        <input type="text" id="voice-duration" class="input-field w-full rounded-md p-2" placeholder="Contoh: Kalimat pendek, paragraf">
                    </div>
                </div>


                <!-- 13. Detail Tambahan -->
                <div class="input-group sm:col-span-2">
                    <label for="details" class="block text-sm font-medium text-gray-300 mb-1">13. Detail Tambahan</label>
                    <div class="flex items-center space-x-2">
                        <textarea id="details" rows="4" class="input-field w-full rounded-md p-2" placeholder="Contoh: ada tanaman hias di latar belakang, sinar matahari masuk dari jendela"></textarea>
                        <button id="gemini-details" class="gemini-btn p-2 rounded-md self-start" title="✨ Gali Ide Detail dengan Gemini">✨</button>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-center">
                <button id="generateBtn" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Generate Prompt
                </button>
            </div>
        </div>

        <!-- Kolom Hasil -->
        <div class="space-y-8">
            <!-- Hasil Bahasa Indonesia -->
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-purple-400">Prompt (Bahasa Indonesia - Editable)</h3>
                    <div class="relative">
                        <button class="btn-copy bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold py-1 px-3 rounded-md" data-target="prompt-id">
                            Salin
                        </button>
                        <div class="copied-feedback">Disalin!</div>
                    </div>
                </div>
                <textarea id="prompt-id" rows="10" class="input-field w-full rounded-md p-3 text-base leading-relaxed" placeholder="Hasil prompt dalam Bahasa Indonesia akan muncul di sini..."></textarea>
            </div>
            <!-- Hasil Bahasa Inggris -->
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-indigo-400">Prompt Final (Bahasa Inggris)</h3>
                    <div class="relative">
                        <button class="btn-copy bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold py-1 px-3 rounded-md" data-target="prompt-en">
                            Salin
                        </button>
                        <div class="copied-feedback">Disalin!</div>
                    </div>
                </div>
                <textarea id="prompt-en" rows="10" class="input-field w-full rounded-md p-3 text-base leading-relaxed" readonly placeholder="Terjemahan cerdas ke Bahasa Inggris akan muncul di sini..."></textarea>
            </div>
        </div>
    </div>
    <footer class="text-center mt-12 text-gray-500 text-sm">
        <p>Dibuat untuk menghasilkan prompt Google Veo yang lebih baik.</p>
    </footer>
</div>

<!-- Notification Element -->
<div id="notification" class="fixed top-5 right-0 mr-5 transform translate-x-[120%] bg-red-600 text-white py-3 px-5 rounded-lg shadow-xl">
    <p id="notification-message"></p>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const generateBtn = document.getElementById('generateBtn');
        const copyBtns = document.querySelectorAll('.btn-copy');

        // --- UI Elements ---
        const dialogueInput = document.getElementById('dialogue');
        const voiceDetailsSection = document.getElementById('voice-details-section');
        const promptIdTextarea = document.getElementById('prompt-id');
        const promptEnTextarea = document.getElementById('prompt-en');
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        let notificationTimeout;

        // --- Contextual UI Logic ---
        dialogueInput.addEventListener('input', () => {
            if (dialogueInput.value.trim() !== '') {
                voiceDetailsSection.classList.add('show');
            } else {
                voiceDetailsSection.classList.remove('show');
            }
        });

        // --- Notification System ---
        function showNotification(message, isError = true) {
            clearTimeout(notificationTimeout);
            notificationMessage.textContent = message;
            notification.style.backgroundColor = isError ? '#dc2626' : '#16a34a'; // red-600 or green-600
            notification.classList.remove('translate-x-[120%]');

            notificationTimeout = setTimeout(() => {
                notification.classList.add('translate-x-[120%]');
            }, 3000);
        }

        // --- Gemini API Call Function ---
        async function callGemini(prompt, button = null) {
            const originalButtonText = button ? button.innerHTML : null;
            if (button) {
                button.disabled = true;
                button.innerHTML = '...';
            }

            const payload = { contents: [{ role: "user", parts: [{ text: prompt }] }] };
            const apiKey = "AIzaSyAKsS-7OJOvE5bzYU1Awf0A5X0FZl_H5Mk"; // Injected by environment
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    throw new Error(`API request failed with status ${response.status} (${response.statusText})`);
                }

                const result = await response.json();
                if (result.candidates?.[0]?.content?.parts?.[0]?.text) {
                    return result.candidates[0].content.parts[0].text.trim();
                } else {
                    // Check for safety ratings or other block reasons
                    if (result.candidates?.[0]?.finishReason === 'SAFETY') {
                        throw new Error('Permintaan diblokir karena alasan keamanan.');
                    }
                    throw new Error('Invalid response structure from API');
                }
            } catch (error) {
                console.error("Gemini API Error:", error);
                showNotification(`${error.message}`);
                return null;
            } finally {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = originalButtonText;
                }
            }
        }

        // --- Gemini Idea Generators ---
        const geminiActionBtn = document.getElementById('gemini-action');
        const geminiPlaceBtn = document.getElementById('gemini-place');
        const geminiDetailsBtn = document.getElementById('gemini-details');

        const subjectInput = document.getElementById('subject');
        const actionInput = document.getElementById('action');
        const placeInput = document.getElementById('place');
        const detailsTextarea = document.getElementById('details');

        geminiActionBtn.addEventListener('click', async () => {
            if (!subjectInput.value.trim()) {
                showNotification('Mohon isi kolom "Subjek" terlebih dahulu.');
                return;
            }
            const prompt = `Berikan satu ide aksi yang singkat dan kreatif untuk subjek: "${subjectInput.value.trim()}". Hanya berikan aksinya saja tanpa penjelasan tambahan.`;
            const suggestion = await callGemini(prompt, geminiActionBtn);
            if (suggestion) actionInput.value = suggestion.replace(/"/g, '');
        });

        geminiPlaceBtn.addEventListener('click', async () => {
            if (!subjectInput.value.trim() || !actionInput.value.trim()) {
                showNotification('Mohon isi kolom "Subjek" dan "Aksi" terlebih dahulu.');
                return;
            }
            const prompt = `Berikan satu ide lokasi atau tempat yang menarik dan spesifik untuk adegan di mana "${subjectInput.value.trim()}" sedang "${actionInput.value.trim()}". Hanya berikan nama lokasinya saja tanpa kalimat pembuka.`;
            const suggestion = await callGemini(prompt, geminiPlaceBtn);
            if (suggestion) placeInput.value = suggestion.replace(/"/g, '');
        });

        geminiDetailsBtn.addEventListener('click', async () => {
            if (!subjectInput.value.trim() || !actionInput.value.trim() || !placeInput.value.trim()) {
                showNotification('Mohon isi kolom "Subjek", "Aksi", dan "Tempat" terlebih dahulu.');
                return;
            }
            const prompt = `Buat daftar singkat 2-3 detail visual sinematik untuk adegan ini: "${subjectInput.value.trim()}" sedang "${actionInput.value.trim()}" di "${placeInput.value.trim()}". Fokus pada detail latar belakang, objek, atau atmosfer. Format sebagai daftar yang dipisahkan koma.`;
            const suggestion = await callGemini(prompt, geminiDetailsBtn);
            if (suggestion) detailsTextarea.value = suggestion.replace(/"/g, '');
        });

        // --- Main Prompt Generation Logic ---
        const translations = {
            'golden hour': 'golden hour', 'blue hour': 'blue hour', 'daylight': 'siang hari', 'night': 'malam hari', 'dawn': 'fajar', 'dusk': 'senja', 'static': 'statis', 'pan left': 'geser kiri', 'pan right': 'geser kanan', 'tilt up': 'miring ke atas', 'tilt down': 'miring ke bawah', 'zoom in': 'perbesar', 'zoom out': 'perkecil', 'dolly in': 'dolly masuk', 'dolly out': 'dolly keluar', 'crane up': 'crane ke atas', 'crane down': 'crane ke bawah', 'tracking shot': 'tembakan pelacakan', 'handheld': 'genggam', 'drone shot': 'tembakan drone', '3D rotation': 'rotasi 3D', 'vortex in': 'pusaran masuk', 'vortex out': 'pusaran keluar', 'rise': 'naik', 'fall': 'jatuh', 'twist clockwise': 'putar searah jarum jam', 'twist counter-clockwise': 'putar berlawanan arah jam', 'horizontal wave': 'gelombang horizontal', 'vertical wave': 'gelombang vertikal', 'roll clockwise': 'bergulir searah jarum jam', 'roll counter-clockwise': 'bergulir berlawanan arah jam', 'wobble': 'goyangan', 'pulse': 'denyut', 'flicker': 'kerlip', 'jitter': 'getaran', 'shake': 'guncangan', 'boomerang': 'bumerang', 'cinematic lighting': 'pencahayaan sinematik', 'dramatic lighting': 'pencahayaan dramatis', 'soft light': 'cahaya lembut', 'hard light': 'cahaya keras', 'natural light': 'cahaya alami', 'neon lighting': 'pencahayaan neon', 'silhouette': 'siluet', 'high key': 'high key', 'low key': 'low key', 'realistic': 'realistis', 'cinematic': 'sinematik', 'animation': 'animasi', 'hyperlapse': 'hyperlapse', 'timelapse': 'timelapse', 'slow motion': 'slow motion', 'documentary': 'dokumenter', 'archival footage': 'rekaman arsip', 'CGI': 'CGI', '8-bit': '8-bit', 'cheerful': 'ceria', 'sad': 'sedih', 'mysterious': 'misterius', 'tense': 'menegangkan', 'romantic': 'romantis', 'epic': 'epik', 'funny': 'lucu', 'peaceful': 'damai', 'nostalgic': 'nostalgia',
        };

        generateBtn.addEventListener('click', async function() {
            const subject = document.getElementById('subject').value.trim();
            const action = document.getElementById('action').value.trim();
            const expression = document.getElementById('expression').value.trim();
            const place = document.getElementById('place').value.trim();
            const time = document.getElementById('time').value;
            const camera = document.getElementById('camera').value;
            const lighting = document.getElementById('lighting').value;
            const style = document.getElementById('style').value;
            const mood = document.getElementById('mood').value;
            const sound = document.getElementById('sound').value.trim();
            const dialogue = document.getElementById('dialogue').value.trim();
            const details = document.getElementById('details').value.trim();

            if (!subject || !action || !place) {
                showNotification('Mohon isi kolom Subjek, Aksi, dan Tempat.');
                return;
            }

            // 1. Generate Indonesian Prompt
            let promptID = `Sebuah video ${style} dengan gaya ${translations[style]}.`;
            promptID += `\n\nSubjek utama adalah ${subject}, yang sedang ${action} dengan ekspresi ${expression}.`;
            promptID += ` Adegan berlatar di ${place} pada waktu ${translations[time]}.`;
            promptID += ` Suasana video terasa ${translations[mood]} dengan pencahayaan ${translations[lighting]}.`;
            promptID += `\n\nGerakan kamera yang digunakan adalah ${translations[camera]}.`;
            if (sound) promptID += ` Diiringi dengan suara/musik: ${sound}.`;

            // Add dialogue and voice details
            if (dialogue) {
                promptID += `\n\nTerdengar kalimat yang diucapkan: "${dialogue}".`;

                const voiceAgeGender = document.getElementById('voice-age-gender').value.trim();
                const voiceAccent = document.getElementById('voice-accent').value.trim();
                const voicePitch = document.getElementById('voice-pitch').value.trim();
                const voiceSpeed = document.getElementById('voice-speed').value.trim();
                const voiceEmotion = document.getElementById('voice-emotion').value.trim();
                const voiceStyle = document.getElementById('voice-style').value.trim();
                const voiceIntonation = document.getElementById('voice-intonation').value.trim();
                const voiceContext = document.getElementById('voice-context').value.trim();
                const voiceLanguage = document.getElementById('voice-language').value.trim();
                const voiceDuration = document.getElementById('voice-duration').value.trim();

                let voiceDescriptionParts = [];
                if (voiceAgeGender) voiceDescriptionParts.push(`jenis kelamin dan usia: ${voiceAgeGender}`);
                if (voiceAccent) voiceDescriptionParts.push(`aksen/dialek: ${voiceAccent}`);
                if (voicePitch) voiceDescriptionParts.push(`nada suara: ${voicePitch}`);
                if (voiceSpeed) voiceDescriptionParts.push(`kecepatan bicara: ${voiceSpeed}`);
                if (voiceEmotion) voiceDescriptionParts.push(`ekspresi emosi: ${voiceEmotion}`);
                if (voiceStyle) voiceDescriptionParts.push(`gaya suara: ${voiceStyle}`);
                if (voiceIntonation) voiceDescriptionParts.push(`intonasi: ${voiceIntonation}`);
                if (voiceContext) voiceDescriptionParts.push(`konteks: ${voiceContext}`);
                if (voiceLanguage) voiceDescriptionParts.push(`bahasa: ${voiceLanguage}`);
                if (voiceDuration) voiceDescriptionParts.push(`durasi: ${voiceDuration}`);

                if (voiceDescriptionParts.length > 0) {
                    promptID += `\nDeskripsi suara pembicara: ${voiceDescriptionParts.join('; ')}.`;
                }
            }

            if (details) promptID += `\n\nDetail tambahan: ${details}.`;
            promptIdTextarea.value = promptID;

            // 2. Translate to English using Gemini
            promptEnTextarea.value = 'Menerjemahkan dengan Gemini...';
            const translationPrompt = `Translate the following Indonesian video prompt into a detailed, grammatically correct, and natural-sounding English prompt suitable for a text-to-video AI like Google Veo. Keep the core details intact but improve the sentence structure for clarity and impact. Add "4k, high detail, photorealistic" at the end. Do not translate any text that is inside quotation marks. Here is the prompt:\n\n---\n\n${promptID}`;

            const translatedPrompt = await callGemini(translationPrompt);

            if (translatedPrompt) {
                promptEnTextarea.value = translatedPrompt;
            } else {
                promptEnTextarea.value = 'Gagal menerjemahkan. Silakan coba lagi.';
            }
        });

        // Copy button functionality
        copyBtns.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.dataset.target;
                const targetTextarea = document.getElementById(targetId);
                targetTextarea.select();
                document.execCommand('copy');

                const feedback = button.nextElementSibling;
                feedback.classList.add('show');
                setTimeout(() => {
                    feedback.classList.remove('show');
                }, 2000);
            });
        });
    });
</script>
</body>
</html>
