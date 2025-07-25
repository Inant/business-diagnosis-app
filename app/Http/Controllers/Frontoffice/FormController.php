<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\UserAnswer;
use App\Models\AiResponse;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\ContentIdea;
use App\Models\ShootingScript;
use Illuminate\Support\Facades\Http;

class FormController extends Controller
{
    public function showForm(Request $request)
    {
        // Ambil query string 'category', default ke "1"
        $category = $request->query('category', '1');

        $questions = Question::where('is_active', 1)
            ->where('category', $category)
            ->orderBy('order')
            ->get();

        return view('frontoffice.form', compact('questions', 'category'));
    }

    public function submitForm(Request $request)
    {
        $user = auth()->user();

        // Simpan session baru
        $session = UserSession::create([
            'user_id' => $user->id,
            'session_id' => (string) Str::uuid(),
            // Kolom prompt/gemini_response di sini bisa dikosongkan atau diisi,
            // tapi akan kita simpan ke ai_responses.
            'prompt' => null,
            'gemini_response' => null,
        ]);

        $answers = $request->input('answers');
        foreach ($answers as $question_id => $answer) {
            UserAnswer::create([
                'user_session_id' => $session->id,
                'question_id' => $question_id,
                'answer' => $answer,
            ]);
        }

        // Generate prompt
        $prompt = $this->generatePrompt($answers);

        // Kirim ke Gemini
        $geminiResponse = $this->sendToGemini($prompt);

        // Simpan ke ai_responses, step diagnosis
        AiResponse::create([
            'user_session_id' => $session->id,
            'step' => 'diagnosis',
            'prompt' => $prompt,
            'ai_response' => $geminiResponse,
        ]);

        // (Opsional: tetap update session, atau skip)
        $session->update([
            'prompt' => $prompt,
            'gemini_response' => $geminiResponse,
        ]);

        return redirect()->route('front.result', ['session' => $session->id]);
    }

    public function showResult($session_id)
    {
        $session = UserSession::findOrFail($session_id);
        $diagnosis = AiResponse::where('user_session_id', $session_id)
            ->where('step', 'diagnosis')->first();

        return view('frontoffice.result', compact('session', 'diagnosis'));
    }

    protected function generatePrompt($answers)
    {
        // Ambil semua pertanyaan yang sesuai dengan jawaban
        $questions = Question::whereIn('id', array_keys($answers))->orderBy('order')->get();

        // Susun isi berdasarkan urutan dan isi jawaban (bisa kosong)
        $contents = [];
        foreach ($questions as $q) {
            $contents[$q->order] = "**{$q->title}:**\n    {$answers[$q->id]}";
        }

        // Ambil seluruh pertanyaan yang tersedia (bukan hanya yang dijawab)
        $allQuestions = Question::orderBy('order')->get();
        $max = $allQuestions->count();

        // Susun list final dari semua pertanyaan, walau belum dijawab
        $list = [];
        foreach ($allQuestions as $q) {
            $list[] = $contents[$q->order] ?? "**{$q->title}:**\n    ";
        }

        $contentString = implode("\n\n", $list);

        $prompt = <<<EOP
#perkenalkan diri anda adalah gurita AI yang dibuat oleh the boss whisperer seorang konsultan bisnis. anda telah diajari selama 5 tahun berbagai bisnis sehingga sudah handal.

# PERAN
Kamu adalah seorang Konsultan Bisnis Strategis dari firma top dunia (seperti McKinsey atau BCG). Kamu sangat tajam, analitis, dan mampu melihat pola yang tidak dilihat orang lain. Tugasmu bukan memberi solusi, tetapi memberikan diagnosis yang akurat dan membuka pikiran.

# KONTEKS
Kamu baru saja menyelesaikan sesi wawancara awal dengan seorang pemilik bisnis. Berikut adalah rangkuman jawaban mentah dari klien tersebut.

$contentString

# TUGAS
1.  **Lakukan Diagnosis Awal:** Berdasarkan keseluruhan konteks, tulis sebuah diagnosis yang tajam. Identifikasi **kontradiksi fundamental** yang paling signifikan dalam bisnis ini (misal: menjual produk premium dengan cara murah, ingin melayani semua orang, dll).
2.  **Rumuskan Masalah Inti:** Sintesiskan diagnosis tersebut menjadi **satu paragraf** yang menjelaskan apa **Masalah Inti (Core Problem)** yang sebenarnya, di mana tantangan yang disebutkan klien hanyalah gejalanya.
3.  **Ajukan Pertanyaan Reflektif:** Akhiri analisis dengan satu **pertanyaan reflektif** yang kuat. Pertanyaan ini harus dirancang untuk membuat pemilik bisnis berhenti sejenak dan berpikir tentang Masalah Inti yang baru saja diungkap.
4.  Analisislah keseluruhan jawaban di atas. Kemudian, sintesiskan semua informasi tersebut menjadi sebuah **"Profil DNA Bisnis"** yang terstruktur. Gunakan field-field berikut untuk ringkasanmu:

* `Nama_Bisnis`: [Jika disebutkan, tulis di sini]
* `Deskripsi_Singkat`: [1-2 kalimat yang menjelaskan inti bisnis]
* `Produk_Layanan_Utama`: [Sebutkan dalam bentuk daftar poin]
* `Target_Pasar_Spesifik`: [Jelaskan siapa target pasarnya secara ringkas]
* `Masalah_Kunci_yang_Diselesaikan`: [Jelaskan nilai jual utama dalam 1 kalimat]
* `Kekuatan_Unik_Teridentifikasi`: [Bukan hanya apa yang diklaim pengguna, tapi apa kekuatan sejati yang kamu simpulkan dari jawaban. Misal: "Pengalaman 20 tahun", "Spesialisasi pada merek premium YKK AP", "Reputasi personal pemilik"]
* `Tantangan_Strategis`: [Satu kalimat yang merangkum masalah inti/strategis mereka]
* `Visi_Jangka_Panjang`: [Tujuan besar mereka dalam 1 kalimat]

# FORMAT & GAYA
* Gunakan heading (###) untuk setiap bagian: `### Diagnosis Awal`, `### Masalah Inti Anda`, dan `### Sebuah Pertanyaan untuk Anda`.
* Gaya bahasa harus lugas, profesional, dan to the point. Hindari basa-basi.
* **PENTING:** Jangan berikan solusi atau rekomendasi marketing apa pun di tahap ini. Fokus 100% pada diagnosis masalah.

#tambahkan copywriting mengenai seminar saya https://www.guritadigital.com/tiktok-mastery-with-ai/
yang akan mengajari AI sebagai sparing partner dalam membuat konten, iklan, copywriting bahkan skript untuk membuat live. Di akhir seminar Anda juga akan diperkenalkan sebuah whatsapp asisten yang bisa menjadi CS mulai memilih barang sampai transaksi selesai dengan AI.
EOP;

        return $prompt;
    }



    protected function sendToGemini($prompt)
    {
        $apiKey = env('GEMINI_API_KEY');
            $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

        $body = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        $response = Http::post($url, $body);

        // Pastikan response Gemini sesuai format yang diharapkan
        if ($response->ok()) {
            // Cek format response Gemini, bisa beda tiap model/versi
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada respon dari Gemini.';
        } else {
            return "Error: " . $response->body();
        }
    }

    public function showSwotForm($session_id)
    {
        ini_set('max_execution_time', 300); // 5 menit
        $session = UserSession::findOrFail($session_id);

        // Cek jika sudah pernah analisa SWOT
        $swot = AiResponse::where('user_session_id', $session_id)->where('step', 'swot')->first();
        if ($swot && $swot->ai_response) {
            return view('frontoffice.swot_result', compact('session', 'swot'));
        }

        // Ambil jawaban-jawaban awal
        $answers = UserAnswer::where('user_session_id', $session_id)->pluck('answer', 'question_id')->toArray();

        // Ambil hasil diagnosis/masalah inti dari ai_responses step 'diagnosis'
        $diagnosis = AiResponse::where('user_session_id', $session_id)->where('step', 'diagnosis')->first();

        // Generate prompt analisa SWOT
        $prompt = $this->generateSwotPrompt($answers, $diagnosis ? $diagnosis->ai_response : '');

        // Kirim ke Gemini
        $response = $this->sendToGemini($prompt);

        // Simpan
        $swot = AiResponse::create([
            'user_session_id' => $session_id,
            'step' => 'swot',
            'prompt' => $prompt,
            'ai_response' => $response,
        ]);

        return view('frontoffice.swot_result', compact('session', 'swot'));
    }

    protected function generateSwotPrompt($answers, $diagnosisText)
    {
        // Ambil pertanyaan dari DB agar urutan benar
        $questions = \App\Models\Question::orderBy('order')->get();

        $jawaban = [];
        foreach ($questions as $i => $q) {
            $jawaban[] = ($i+1) . '. ' . $q->title . ': ' . ($answers[$q->id] ?? '-');
        }
        $jawabanStr = implode("\n", $jawaban);

        return <<<EOP
#perkenalkan diri anda adalah gurita AI yang dibuat oleh the boss whisperer seorang konsultan bisnis. anda telah diajari selama 5 tahun berbagai bisnis sehingga sudah handal.

# PERAN
Kamu adalah seorang Konsultan Pemasaran Digital yang berpengalaman dan kreatif. Kamu adalah seorang ahli strategi sekaligus praktisi. Tugasmu adalah mengubah diagnosis masalah menjadi sebuah rencana aksi yang jelas, praktis, dan menginspirasi.

# KONTEKS
Kamu akan melanjutkan sesi konsultasi dengan klien yang sama. Kamu sudah memiliki pemahaman penuh tentang bisnis mereka dan akar masalahnya.

**BAGIAN A: KONTEKS AWAL KLIEN**
$jawabanStr

**BAGIAN B: HASIL DIAGNOSIS SEBELUMNYA**
Masalah Inti yang telah teridentifikasi dari analisis pertama adalah:
$diagnosisText

# TUGAS
Berdasarkan keseluruhan konteks di atas (Bagian A dan B), buatkan sebuah **Peta Jalan Marketing** yang komprehensif. Peta jalan ini harus menjadi solusi praktis untuk Masalah Inti yang ada. Rencana harus mencakup:

1.  **Analisis SWOT yang Jujur:** Berikan analisis SWOT dalam format poin-poin.
2.  **Rumusan Ulang Unique Selling Proposition (USP):** Ciptakan satu kalimat USP baru yang kuat dan relevan.
3.  **Profil Persona Pembeli Utama:** Buat satu persona pembeli yang detail.
4.  **Strategi Konten 3 Pilar Berkelanjutan:** Berikan 2-3 ide konten konkret untuk setiap pilar (Edukasi, Interaksi, Inspirasi).
5.  **Analisis Digital Marketing Tambahan:**
    * **Peta Perjalanan Pelanggan (Mini):** Jelaskan 3 tahap (Awareness, Consideration, Decision) dengan satu ide aktivitas marketing per tahap.
    * **Rekomendasi Kanal Digital Utama:** Berikan rekomendasi untuk 3 kanal digital utama dengan urutan prioritas sebagai berikut:
        * **1. Media Sosial Visual (Pilih salah satu antara TikTok atau Instagram):** Jelaskan mengapa platform ini cocok untuk menjangkau audiens dan menampilkan produk secara visual. Berikan satu ide konten pembuka yang kuat.
        * **2. Website Profesional:** Jelaskan peran website sebagai "rumah digital", pusat informasi, dan alat untuk membangun kredibilitas jangka panjang. Sebutkan 3 halaman wajib yang harus ada (misal: Beranda, Portofolio Proyek, Kontak).

# FORMAT & GAYA
* Gunakan heading (###) untuk setiap bagian analisis (### Analisis SWOT, ### Unique Selling Proposition Anda, dst.).
* Gaya bahasa harus positif, memotivasi, dan penuh dengan saran yang bisa ditindaklanjuti.
* Akhiri seluruh laporan dengan satu paragraf penutup berjudul `### Langkah Pertama Anda`, yang menyarankan satu tindakan paling penting yang harus segera dilakukan.

#tambahkan copywriting mengenai seminar saya https://www.guritadigital.com/tiktok-mastery-with-ai/
yang akan mengajari AI sebagai sparing partner dalam membuat konten, iklan, copywriting bahkan skript untuk membuat live. Di akhir seminar Anda juga akan diperkenalkan sebuah whatsapp asisten yang bisa menjadi CS mulai memilih barang sampai transaksi selesai dengan AI.
EOP;
    }

    public function showContentPlanForm($session_id)
    {
        $session = UserSession::findOrFail($session_id);
        $contentPlan = AiResponse::where('user_session_id', $session_id)
            ->where('step', 'content_plan')->first();

        // Jika sudah pernah generate, tampilkan hasil
        if ($contentPlan && $contentPlan->ai_response) {
            // Ambil data dari ContentIdea table
            $contentIdeas = ContentIdea::where('user_session_id', $session_id)->orderBy('hari_ke')->get();
            return view('frontoffice.content_plan_result', compact('session', 'contentIdeas'));
        }

        return view('frontoffice.content_plan_form', compact('session'));
    }

    public function generateContentPlan(Request $request, $session_id)
    {
        $session = UserSession::findOrFail($session_id);

        $days = $request->input('days', 7);

        // Cek apakah sudah ada content ideas untuk session ini
        $existingContentIdeas = ContentIdea::where('user_session_id', $session_id)->get();

        // Jika belum ada, generate baru
        if ($existingContentIdeas->isEmpty()) {
            // Ambil semua data yang dibutuhkan:
            $answers = UserAnswer::where('user_session_id', $session_id)->pluck('answer', 'question_id')->toArray();
            $questions = Question::orderBy('order')->get();

            // Masalah inti, SWOT, USP, Persona dari ai_responses SWOT step
            $diagnosis = AiResponse::where('user_session_id', $session_id)->where('step', 'diagnosis')->first();
            $swot = AiResponse::where('user_session_id', $session_id)->where('step', 'swot')->first();

            $prompt = $this->generateContentPlanPrompt(
                $questions, $answers,
                $diagnosis?->ai_response,
                $swot?->ai_response,
                $days
            );

            $geminiResponse = $this->sendToGemini($prompt);

            $jsonString = $this->extractJsonFromResponse($geminiResponse);

            // --- Convert ke array ---
            $kontenArray = json_decode($jsonString, true);

            // Safety: handle jika error parsing
            if (!is_array($kontenArray)) {
                throw new \Exception('Gagal parsing JSON dari Gemini.');
            }

            // --- Simpan ke table content_ideas ---
            foreach ($kontenArray as $item) {
                ContentIdea::updateOrCreate(
                    [
                        'user_session_id' => $session_id,
                        'hari_ke' => $item['Hari_ke'],
                    ],
                    [
                        'judul_konten' => $item['Judul_Konten'],
                        'pilar_konten' => $item['Pilar_Konten'],
                        'hook' => $item['Hook'],
                        'script_poin_utama' => is_array($item['Script_Poin_Utama'])
                            ? json_encode($item['Script_Poin_Utama'])
                            : $item['Script_Poin_Utama'],
                        'call_to_action' => $item['Call_to_Action_(CTA)'],
                        'rekomendasi_format' => $item['Rekomendasi_Format'],
                    ]
                );
            }

            // Simpan di ai_responses step content_plan
            AiResponse::create([
                'user_session_id' => $session_id,
                'step' => 'content_plan',
                'prompt' => $prompt,
                'ai_response' => $jsonString
            ]);

            // Ambil data yang baru disimpan
            $contentIdeas = ContentIdea::where('user_session_id', $session_id)->orderBy('hari_ke')->get();
        } else {
            // Jika sudah ada, gunakan data existing
            $contentIdeas = $existingContentIdeas->sortBy('hari_ke');
        }

        return view('frontoffice.content_plan_result', compact('session', 'contentIdeas'));
    }

    protected function generateContentPlanPrompt($questions, $answers, $diagnosis, $swot, $days)
    {
        // Jawaban user untuk 8 pertanyaan awal (bagian A)
        $jawabanList = [];
        foreach ($questions as $i => $q) {
            $jawabanList[] = ($i+1) . '. ' . $q->title . ': ' . ($answers[$q->id] ?? '-');
        }
        $jawabanStr = implode("\n", $jawabanList);

        // **ASSET MARKETING**: isi dengan output dari diagnosis/SWOT
        $swotStr = $swot ?: '-';
        $diagnosisStr = $diagnosis ?: '-';

        // Durasi konten
        $durasi = $days;

        return <<<EOP
# PERAN
Kamu adalah seorang Social Media Content Strategist dan Creative Copywriter yang sangat terstruktur. Kamu ahli dalam mengubah strategi menjadi ide konten harian yang lengkap dengan script, hook, dan CTA.

# KONTEKS
Kamu akan membuat rencana konten untuk sebuah bisnis. Berikut adalah semua data strategis yang kamu butuhkan:

**BAGIAN A: PROFIL BISNIS LENGKAP**
$jawabanStr

**BAGIAN B: ASET MARKETING YANG TELAH DISIMPAN**
* **Masalah Inti Bisnis:** $diagnosisStr
* **Analisis SWOT:** $swotStr

**BAGIAN C: PERMINTAAN KONTEN DINAMIS**
* **Durasi Rencana Konten:** Buatkan rencana untuk **$durasi** hari.

# TUGAS
1.  Buatkan **Rencana Kalender Konten** untuk durasi yang diminta pada **Bagian C**.
2.  Setiap hari harus memiliki satu ide konten yang unik, relevan dengan Pilar Konten (Edukasi, Interaksi, Inspirasi), dan berbicara langsung kepada Persona Pembeli.
3.  **PENTING:** Untuk setiap ide konten, pecah informasinya ke dalam **struktur field** berikut:
    * `Hari_ke`: (Nomor urut hari)
    * `Pilar_Konten`: (Pilih salah satu: Edukasi, Interaksi, atau Inspirasi)
    * `Judul_Konten`: (Judul yang menarik dan ringkas)
    * `Hook`: (Satu kalimat pembuka yang sangat kuat untuk 1-3 detik pertama video/post)
    * `Script_Poin_Utama`: (Isi konten dalam 3-4 poin ringkas)
    * `Call_to_Action_(CTA)`: (Ajakan bertindak yang spesifik, misal: "Komen di bawah", "Klik link di bio", "Share ke temanmu")
    * `Rekomendasi_Format`: (Saran format, misal: Video Reels, Carousel Instagram, TikTok Story, Website Blog Post)
Prioritaskan konten dari pilar Edukasi dan Inspirasi. Buat penyebutan produk secara halus dan tidak langsung.

# FORMAT & GAYA
* **WAJIB:** Sajikan seluruh output dalam format **JSON (JavaScript Object Notation)** yang benar-benar valid. Buat sebuah array (daftar) di mana setiap objek di dalamnya adalah satu hari dari rencana konten, dengan key yang sesuai dengan nama field yang diminta di atas (`Hari_ke`, `Pilar_Konten`, dll).
* **AWALI** output langsung dengan tanda kurung siku `[` dan **AKHIRI** dengan kurung siku penutup `]`, tanpa teks apa pun di luar array.
* Setiap field string **wajib** menggunakan tanda kutip dua `"` (double quotes).
* **JANGAN** menambahkan teks, penjelasan, catatan, komentar, markdown (seperti ```json), atau karakter lain di luar array JSON.
* **JANGAN** memakai trailing comma (koma di akhir array/objek).
* Jika ada karakter khusus di dalam string (misal tanda kutip di dalam value), gunakan escape karakter sesuai format JSON (`\"`).
* Format JSON ini akan memastikan aplikasi Anda dapat dengan mudah mem-parsing data dan menampilkannya di field yang sudah Anda siapkan.

* **Contoh format output JSON untuk 2 hari:**
```json
[
  {
    "Hari_ke": 1,
    "Pilar_Konten": "",
    "Judul_Konten": "",
    "Hook": "",
    "Script_Poin_Utama": [],
    "Call_to_Action_(CTA)": "",
    "Rekomendasi_Format": ""
  },
  {
    "Hari_ke": 2,
    "Pilar_Konten": "",
    "Judul_Konten": "",
    "Hook": "",
    "Script_Poin_Utama": [],
    "Call_to_Action_(CTA)": "",
    "Rekomendasi_Format": ""
  }
]
EOP;
    }

    protected function extractJsonFromResponse($responseText)
    {
        // Hilangkan ```json di awal dan ``` di akhir
        $responseText = trim($responseText);
        $responseText = preg_replace('/^```json\s*/', '', $responseText); // buang di awal
        $responseText = preg_replace('/```$/', '', $responseText); // buang di akhir

        // Cari blok array JSON pertama
        $jsonStart = strpos($responseText, '[');
        $jsonEnd = strrpos($responseText, ']');
        if ($jsonStart !== false && $jsonEnd !== false) {
            return substr($responseText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }
        return null;
    }

    public function history()
    {
        $user = auth()->user();
        // Ambil semua sesi analisa milik user (urutkan terbaru)
        $sessions = UserSession::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Ambil ai_responses untuk setiap session sekaligus (diagnosis, swot, content_plan)
        $sessions->load(['aiResponses']);

        return view('frontoffice.history', compact('sessions'));
    }

    public function showShootingScriptForm($contentIdea)
    {
        $contentIdea = ContentIdea::findOrFail($contentIdea);
        $session = UserSession::findOrFail($contentIdea->user_session_id);

        // Ambil data DNA Bisnis & USP dari AI sebelumnya
        $diagnosis = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'swot')->first();

        return view('frontoffice.shooting_script_form', compact('session', 'contentIdea', 'diagnosis', 'swot'));
    }

    public function generateShootingScript(Request $request, $contentIdea)
    {
        $contentIdea = ContentIdea::findOrFail($contentIdea);
        $session = UserSession::findOrFail($contentIdea->user_session_id);

        // Validasi input
        $request->validate([
            'gaya_pembawaan' => 'required|string',
            'target_durasi' => 'required|string',
            'penyebutan_audiens' => 'required|string'
        ]);

        // Ambil data DNA Bisnis & USP dari AI sebelumnya
        $diagnosis = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'swot')->first();

        // Cek apakah sudah ada shooting script untuk content idea ini
        $existingScript = ShootingScript::where('content_idea_id', $contentIdea->id)->first();

        if (!$existingScript) {
            // Generate prompt shooting script
            $prompt = $this->generateShootingScriptPrompt(
                $contentIdea,
                $diagnosis?->ai_response ?? '',
                $swot?->ai_response ?? '',
                $request->input('gaya_pembawaan'),
                $request->input('target_durasi'),
                $request->input('penyebutan_audiens')
            );

            // Kirim ke Gemini
            $geminiResponse = $this->sendToGemini($prompt);

            // Extract JSON dari response
            $jsonString = $this->extractJsonFromShootingScriptResponse($geminiResponse);

            // Validate JSON
            $scriptArray = json_decode($jsonString, true);
            if (!is_array($scriptArray)) {
                throw new \Exception('Gagal parsing JSON shooting script dari Gemini.');
            }

            // Simpan ke database
            $shootingScript = ShootingScript::create([
                'content_idea_id' => $contentIdea->id,
                'user_session_id' => $contentIdea->user_session_id, // Tambahkan ini
                'gaya_pembawaan' => $request->input('gaya_pembawaan'),
                'target_durasi' => $request->input('target_durasi'),
                'penyebutan_audiens' => $request->input('penyebutan_audiens'),
                'prompt' => $prompt,
                'ai_response' => $jsonString,
//                'script_data' => json_encode($scriptArray)
                'script_json' => $jsonString // Tambahkan ini
            ]);
        } else {
            $shootingScript = $existingScript;
            $scriptArray = $this->cleanAndDecodeJson($shootingScript->script_json);
        }

        return view('frontoffice.shooting_script_result', compact('session', 'contentIdea', 'shootingScript', 'scriptArray'));
    }

    protected function generateShootingScriptPrompt($contentIdea, $diagnosis, $swot, $gayaPembawaan, $targetDurasi, $penyebutanAudiens)
    {
        $poinUtama = json_decode($contentIdea->script_poin_utama, true) ?? [];
        $poinUtamaStr = implode("\n", array_map(function($poin, $index) {
            return ($index + 1) . ". " . $poin;
        }, $poinUtama, array_keys($poinUtama)));

        return <<<EOP
# PERAN DAN TUJUAN
Anda adalah seorang Sutradara dan Script Writer untuk konten media sosial (Reels/TikTok) yang sangat berpengalaman. Tugas Anda adalah mengubah konsep konten mentah menjadi sebuah shooting script yang detail, menarik, dan siap dieksekusi, dalam format JSON.

# INPUT: KONSEP KONTEN
* **DNA BISNIS :** $diagnosis
* **USP Bisnis :** $swot
* **Judul Konten:** {$contentIdea->judul_konten}
* **Hook:** {$contentIdea->hook}
* **Poin Utama:**
$poinUtamaStr
* **Call to Action (CTA):** {$contentIdea->call_to_action}

# INPUT: KRITERIA SCRIPT
* **Gaya Pembawaan:** $gayaPembawaan
* **Target Durasi Total:** $targetDurasi
* **Penyebutan Audiens:** $penyebutanAudiens

# FORMAT OUTPUT (**SANGAT WAJIB**)
1. OUTPUT HANYA dalam format JSON **asli** (valid JSON array), **bukan string JSON** dan **tanpa karakter escape**.
2. Output TIDAK BOLEH berisi tanda kutip luar, karakter `\n`, atau escape sequence lainnya.
3. Output HARUS berupa **array JSON** yang valid secara langsung, misal:

[
  {
    "no": 1,
    "durasi": 3,
    "script": "Contoh script di sini",
    "kategori": "Hook"
  },
  {
    "no": 2,
    "durasi": 5,
    "script": "Contoh script kedua",
    "kategori": "Isi"
  }
]

4. Setiap elemen harus memiliki key: `no` (integer), `durasi` (detik), `script` (string), `kategori` (string: "Hook", "Isi", "Penutup", "CTA").
5. Total `durasi` harus mendekati $targetDurasi detik.
6. **PENTING: Jika Anda salah format, ULANGI dan hanya kembalikan array JSON yang valid, tanpa penjelasan apapun.**

# MULAI
EOP;
    }


    protected function extractJsonFromShootingScriptResponse($responseText)
    {
        // Hilangkan ```json di awal dan ``` di akhir
        $responseText = trim($responseText);
        $responseText = preg_replace('/^```json\s*/', '', $responseText);
        $responseText = preg_replace('/```$/', '', $responseText);

        // Cari blok array JSON pertama
        $jsonStart = strpos($responseText, '[');
        $jsonEnd = strrpos($responseText, ']');
        if ($jsonStart !== false && $jsonEnd !== false) {
            return substr($responseText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        // Jika tidak ada array, coba cari object
        $jsonStart = strpos($responseText, '{');
        $jsonEnd = strrpos($responseText, '}');
        if ($jsonStart !== false && $jsonEnd !== false) {
            return substr($responseText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        return null;
    }

    private function cleanAndDecodeJson($jsonString)
    {
        // Hilangkan escape characters dan newlines
        $cleaned = str_replace(['\n', '\"', '\\'], ['', '"', ''], $jsonString);

        // Jika masih ada masalah, coba dengan stripslashes
        if (!json_decode($cleaned)) {
            $cleaned = stripslashes($jsonString);
        }

        return json_decode($cleaned, true) ?? [];
    }


}
