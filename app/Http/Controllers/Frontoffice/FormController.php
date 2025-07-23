<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\UserAnswer;
use App\Models\AiResponse;
use Illuminate\Support\Str;
use App\Models\Question;
use Illuminate\Support\Facades\Http;

class FormController extends Controller
{
    public function showForm()
    {
        $questions = Question::where('is_active', 1)->orderBy('order')->get();
        return view('frontoffice.form', compact('questions'));
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
        if ($contentPlan && $contentPlan->ai_response_json) {
            return view('frontoffice.content_plan_result', compact('session', 'contentPlan'));
        }

        // Atau tampilkan form pilih durasi (default 30 hari)
        return view('frontoffice.content_plan_form', compact('session'));
    }

    public function generateContentPlan(Request $request, $session_id)
    {
        $session = UserSession::findOrFail($session_id);

        $days = $request->input('days', 7);

        // Ambil semua data yang dibutuhkan:
        $answers = UserAnswer::where('user_session_id', $session_id)->pluck('answer', 'question_id')->toArray();
        $questions = Question::orderBy('order')->get();

        // Masalah inti, SWOT, USP, Persona dari ai_responses SWOT step
        $diagnosis = AiResponse::where('user_session_id', $session_id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $session_id)->where('step', 'swot')->first();

        // Extract output (misal: regex atau parsing sederhana) untuk USP dan Persona jika ingin lebih advance,
        // atau langsung lampirkan output response SWOT untuk bagian SWOT/USP/Persona.

        $prompt = $this->generateContentPlanPrompt(
            $questions, $answers,
            $diagnosis?->ai_response,
            $swot?->ai_response,
            $days
        );

        $geminiResponse = $this->sendToGemini($prompt);

        // **Extract json jika perlu:** (pastikan Gemini benar-benar mengirim JSON!)
        $jsonStart = strpos($geminiResponse, '[');

        $jsonString = $this->extractJsonFromResponse($geminiResponse);

        // Simpan di ai_responses step content_plan
        $contentPlan = AiResponse::create([
            'user_session_id' => $session_id,
            'step' => 'content_plan',
            'prompt' => $prompt,
            'ai_response' => $jsonString
//            'ai_response_json' => $jsonString,
        ]);

        return view('frontoffice.content_plan_result', compact('session', 'contentPlan'));
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




}
