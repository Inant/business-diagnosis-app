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
use App\Models\ContentPlan;
use App\Models\ShootingScript;
use App\Models\AdsResult;
use Illuminate\Support\Facades\Http;
use App\Services\GeminiApiService; // Import service baru

class FormController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiApiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function dashboard()
    {
        $user = auth()->user();

        // Ambil sesi utama user (analisa bisnis)
        $mainSession = UserSession::where('user_id', $user->id)
            ->with(['aiResponses' => function($query) {
                $query->whereIn('step', ['diagnosis', 'swot']);
            }])
            ->first();

        $data = [
            'mainSession' => $mainSession,
            'contentPlans' => collect(),
            'stats' => [
                'total_content_plans' => 0,
                'total_content_ideas' => 0,
                'this_month_plans' => 0,
            ]
        ];

        if ($mainSession) {
            // Ambil content plans terbaru
            $contentPlans = ContentPlan::where('user_id', $user->id)
                ->with(['contentIdeas'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Hitung statistik
            $allContentPlans = ContentPlan::where('user_id', $user->id)->get();
            $totalContentIdeas = ContentIdea::whereIn('content_plan_id', $allContentPlans->pluck('content_plan_id'))->count();
            $thisMonthPlans = ContentPlan::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            // Extract profil DNA bisnis
            $profilDna = null;
            $diagnosis = $mainSession->aiResponses->where('step', 'diagnosis')->first();
            if ($diagnosis && $diagnosis->profil_dna_bisnis) {
                $profilDna = json_decode($diagnosis->profil_dna_bisnis, true);
            }

            $data = [
                'mainSession' => $mainSession,
                'contentPlans' => $contentPlans,
                'profilDna' => $profilDna,
                'stats' => [
                    'total_content_plans' => $allContentPlans->count(),
                    'total_content_ideas' => $totalContentIdeas,
                    'this_month_plans' => $thisMonthPlans,
                ]
            ];
        }

        return view('frontoffice.dashboard', $data);
    }

    public function showForm(Request $request)
    {
        $user = auth()->user();

        // Default category berdasar plan user
        $defaultCategory = $user && $user->plan === 'pro' ? '1' : '2';

        // Query string bisa override, tapi default pakai logic di atas
        $category = $request->query('category', $defaultCategory);

        $questions = Question::where('is_active', 1)
            ->where('category', $category)
            ->orderBy('order')
            ->get();

        return view('frontoffice.form', compact('questions', 'category'));
    }

    public function submitForm(Request $request)
    {
        $user = auth()->user();

        $session = UserSession::create([
            'user_id' => $user->id,
            'session_id' => (string) Str::uuid(),
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

        $prompt = $this->generatePrompt($answers);

        // Debug: Log sebelum panggil service
        \Log::info('About to call Gemini service');

        $result = $this->geminiService->generateContent(
            $prompt,
            $user->id,
            $session->id,
            'diagnosis'
        );

        // Debug: Log hasil service
        \Log::info('Gemini service result:', $result);

        $profilDnaBisnis = $this->extractJson($result['content']);
//        return $profilDnaBisnis;

        // Debug: Check apa yang akan disimpan
        $dataToSave = [
            'user_session_id' => $session->id,
            'step' => 'diagnosis',
            'prompt' => $prompt,
            'ai_response' => $result['content'],
            'profil_dna_bisnis' => !empty($profilDnaBisnis) ? $profilDnaBisnis : null,
            'tokens_used' => $result['usage']['total_tokens'],
            'cost_idr' => $result['usage']['total_cost_idr'],
            'response_time_ms' => $result['usage']['response_time_ms']
        ];

        \Log::info('Data to save to ai_responses:', $dataToSave);

        AiResponse::create($dataToSave);

        $session->update([
            'prompt' => $prompt,
            'gemini_response' => $result['content'],
        ]);

        return redirect()->route('front.result', ['session' => $session->id]);
    }

    protected function extractJson($responseText)
    {
        // Cari blok ```json ... ```
        if (preg_match('/```json\s*(\{.*\})\s*```/is', $responseText, $matches)) {
            return $matches[1]; // Sudah dalam bentuk string JSON
        }
        // Fallback: cari kurung kurawal pertama sampai terakhir
        if (preg_match('/(\{.*\})/s', $responseText, $matches)) {
            return $matches[1];
        }
        return null;
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
# PERKENALKAN DIRI
Anda adalah Gurita AI yang dibuat oleh The Boss Whisperer, seorang konsultan bisnis kawakan. Anda telah diajari selama 5 tahun berbagai bisnis sehingga sudah sangat handal.

# PERAN
Kamu adalah Konsultan Bisnis Strategis (ala McKinsey/BCG), sangat analitis, dan melihat pola yang tidak kasat mata. Tugasmu bukan memberi solusi, tetapi memberikan diagnosis yang tajam.

# KONTEKS
Baru saja selesai sesi wawancara dengan pemilik bisnis. Berikut adalah jawaban-jawaban mentah klien:

$contentString

# TUGAS
1. **Diagnosis Awal:** Tulis diagnosis paling tajam. Identifikasi *kontradiksi fundamental* paling signifikan dalam bisnis ini.
2. **Masalah Inti:** Sintesis diagnosis menjadi **satu paragraf** yang menjelaskan apa masalah inti mereka (core problem) di mana tantangan hanyalah gejala.
3. **Pertanyaan Reflektif:** Tulis satu pertanyaan reflektif yang benar-benar menggelitik pemilik bisnis tentang masalah inti yang kamu temukan.
4. **Profil DNA Bisnis:** Analisa keseluruhan jawaban, lalu sintesis dalam format **JSON VALID** dengan field sebagai berikut:

```json
{
  "Nama_Bisnis": "[Jika disebutkan, tulis di sini]",
  "Deskripsi_Singkat": "[1-2 kalimat tentang inti bisnis]",
  "Produk_Layanan_Utama": ["...daftar poin..."],
  "Target_Pasar_Spesifik": "[deskripsi ringkas target pasar]",
  "Masalah_Kunci_yang_Diselesaikan": "[nilai jual utama dalam 1 kalimat]",
  "Kekuatan_Unik_Teridentifikasi": "[kekuatan sejati bisnis, bukan sekadar klaim]",
  "Tantangan_Strategis": "[1 kalimat masalah inti/strategis]",
  "Visi_Jangka_Panjang": "[1 kalimat tujuan besar]"
}

# FORMAT & GAYA
* Untuk diagnosis, masalah inti, dan pertanyaan reflektif, gunakan heading: ### Diagnosis Awal, ### Masalah Inti Anda, dan ### Sebuah Pertanyaan untuk Anda.
* Untuk Profil DNA Bisnis, tampilkan HANYA dalam format JSON valid seperti di atas. Tidak perlu penjelasan, tidak perlu heading tambahan.
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
        ini_set('max_execution_time', 300);
        $session = UserSession::findOrFail($session_id);
        $user = auth()->user();

        // Cek jika sudah pernah analisa SWOT
        $swot = AiResponse::where('user_session_id', $session_id)->where('step', 'swot')->first();
        if ($swot && $swot->ai_response) {
            return view('frontoffice.swot_result', compact('session', 'swot'));
        }

        // Ambil jawaban-jawaban awal
        $answers = UserAnswer::where('user_session_id', $session_id)->pluck('answer', 'question_id')->toArray();

        // Ambil hasil diagnosis
        $diagnosis = AiResponse::where('user_session_id', $session_id)->where('step', 'diagnosis')->first();

        // Generate prompt analisa SWOT
        $prompt = $this->generateSwotPrompt($answers, $diagnosis ? $diagnosis->ai_response : '');

        // Kirim ke Gemini dengan tracking
        $result = $this->geminiService->generateContent(
            $prompt,
            $user->id,
            $session_id,
            'swot'
        );

        // Simpan
        $swot = AiResponse::create([
            'user_session_id' => $session_id,
            'step' => 'swot',
            'prompt' => $prompt,
            'ai_response' => $result['content'],
            'tokens_used' => $result['usage']['total_tokens'],
            'cost_idr' => $result['usage']['total_cost_idr'],
            'response_time_ms' => $result['usage']['response_time_ms']
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

1.  **Analisis SWOT yang Jujur:** Berikan analisis SWOT dalam format point dan narasi yang meyakinkan serta berikan perumpamaan yang relevan dan dekat dengan pengguna. Analisa menyebutkan SWOT dalam bahasa Indonesia.
2.  **Rumusan Ulang Unique Selling Proposition (USP):** Ciptakan satu kalimat USP baru yang kuat dan relevan. Pada bagian selanjutnya jelaskan kalimat yang Anda ciptakan agar user bisa memahami hasil Anda.
3.  **Profil Persona Pembeli Utama:** Buat tiga sampai empat persona pembeli yang detail dan memungkinkan menjadi target market anda.
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

    public function contentHistory()
    {
        $user = auth()->user();

        // Ambil sesi utama user (satu-satunya sesi)
        $mainSession = UserSession::where('user_id', $user->id)->first();

        if (!$mainSession) {
            // Jika belum ada sesi, redirect ke form analisa awal
            return redirect()->route('front.form')->with('info', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        // Ambil semua content plans dari user ini
        $contentPlans = ContentPlan::where('user_id', $user->id)
            ->with('contentIdeas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontoffice.content_history', compact('mainSession', 'contentPlans'));
    }

    public function showContentPlanForm($session_id = null)
    {
        $user = auth()->user();

        // Ambil sesi utama user
        $session = UserSession::where('user_id', $user->id)->first();
        if (!$session) {
            return redirect()->route('front.form')->with('info', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        // Cek apakah sudah ada diagnosis dan SWOT
        $diagnosis = AiResponse::where('user_session_id', $session->id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $session->id)->where('step', 'swot')->first();

        if (!$diagnosis) {
            return redirect()->route('front.form')->with('error', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        if (!$swot) {
            return redirect()->route('front.swot.form', $session->id)->with('error', 'Silakan lengkapi analisa SWOT terlebih dahulu');
        }

        return view('frontoffice.content_plan_form', compact('session'));
    }

    public function generateContentPlan(Request $request)
    {
        $user = auth()->user();

        // Ambil sesi utama user
        $session = UserSession::where('user_id', $user->id)->first();
        if (!$session) {
            return redirect()->route('front.form')->with('info', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        $days = $request->input('days', 7);
        $tujuan_pembuatan_konten = $request->input('tujuan_pembuatan_konten', null);

        // Generate unique identifier untuk content plan ini
        $contentPlanId = 'cp_' . time() . '_' . $session->id;

        // Ambil data yang diperlukan
        $answers = UserAnswer::where('user_session_id', $session->id)->pluck('answer', 'question_id')->toArray();
        $questions = Question::orderBy('order')->get();
        $diagnosis = AiResponse::where('user_session_id', $session->id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $session->id)->where('step', 'swot')->first();

        $prompt = $this->generateContentPlanPrompt(
            $questions, $answers,
            $diagnosis?->ai_response,
            $swot?->ai_response,
            $days,
            $tujuan_pembuatan_konten
        );

        // Kirim ke Gemini
        $result = $this->geminiService->generateContent(
            $prompt,
            $user->id,
            $session->id,
            'content_plan'
        );

        $jsonString = $this->extractJsonFromResponse($result['content']);
        $kontenArray = json_decode($jsonString, true);

        if (!is_array($kontenArray)) {
            throw new \Exception('Gagal parsing JSON dari Gemini.');
        }

        // Simpan ke table content_plans
        $contentPlan = ContentPlan::create([
            'content_plan_id' => $contentPlanId,
            'user_session_id' => $session->id,
            'user_id' => $user->id,
            'days' => $days,
            'tujuan_pembuatan_konten' => $tujuan_pembuatan_konten,
            'prompt' => $prompt,
            'ai_response' => $jsonString,
            'tokens_used' => $result['usage']['total_tokens'],
            'cost_idr' => $result['usage']['total_cost_idr'],
            'response_time_ms' => $result['usage']['response_time_ms']
        ]);

        // Simpan ke table content_ideas
        foreach ($kontenArray as $item) {
            ContentIdea::create([
                'user_session_id' => $session->id,
                'content_plan_id' => $contentPlanId,
                'hari_ke' => $item['Hari_ke'],
                'judul_konten' => $item['Judul_Konten'],
                'pilar_konten' => $item['Pilar_Konten'],
                'hook' => $item['Hook'],
                'script_poin_utama' => is_array($item['Script_Poin_Utama'])
                    ? $item['Script_Poin_Utama']
                    : json_decode($item['Script_Poin_Utama'], true),
                'call_to_action' => $item['Call_to_Action_(CTA)'],
                'rekomendasi_format' => $item['Rekomendasi_Format'],
            ]);
        }

        return redirect()->route('front.content.detail', $contentPlanId)
            ->with('success', 'Konten berhasil di-generate!');
    }

    protected function generateContentPlanPrompt($questions, $answers, $diagnosis, $swot, $days, $tujuan_pembuatan_konten)
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
    * **Durasi Rencana Konten:** Buatkan rencana untuk **$durasi** hari

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

    public function showContentDetail($contentPlanId)
    {
        $user = auth()->user();

        $contentPlan = ContentPlan::where('content_plan_id', $contentPlanId)
            ->where('user_id', $user->id)
            ->with(['userSession', 'contentIdeas' => function($query) {
                $query->orderBy('hari_ke');
            }])
            ->firstOrFail();

        return view('frontoffice.content_plan_result', compact('contentPlan'));
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
        $user = auth()->user();

        $request->validate([
            'gaya_pembawaan' => 'required|string',
            'target_durasi' => 'required|string',
            'penyebutan_audiens' => 'required|string'
        ]);

        $diagnosis = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $contentIdea->user_session_id)->where('step', 'swot')->first();

        $existingScript = ShootingScript::where('content_idea_id', $contentIdea->id)->first();

        if (!$existingScript) {
            $prompt = $this->generateShootingScriptPrompt(
                $contentIdea,
                $diagnosis?->ai_response ?? '',
                $swot?->ai_response ?? '',
                $request->input('gaya_pembawaan'),
                $request->input('target_durasi'),
                $request->input('penyebutan_audiens')
            );

            // Kirim ke Gemini dengan tracking
            $result = $this->geminiService->generateContent(
                $prompt,
                $user->id,
                $contentIdea->user_session_id,
                'shooting_script'
            );

            $jsonString = $this->extractJsonFromShootingScriptResponse($result['content']);
            $scriptArray = json_decode($jsonString, true);

            if (!is_array($scriptArray)) {
                throw new \Exception('Gagal parsing JSON shooting script dari Gemini.');
            }

            $shootingScript = ShootingScript::create([
                'content_idea_id' => $contentIdea->id,
                'user_session_id' => $contentIdea->user_session_id,
                'gaya_pembawaan' => $request->input('gaya_pembawaan'),
                'target_durasi' => $request->input('target_durasi'),
                'penyebutan_audiens' => $request->input('penyebutan_audiens'),
                'prompt' => $prompt,
                'raw_ai_response' => $jsonString,
                'script_json' => $jsonString,
                'tokens_used' => $result['usage']['total_tokens'],
                'cost_idr' => $result['usage']['total_cost_idr'],
                'response_time_ms' => $result['usage']['response_time_ms']
            ]);
        } else {
            $shootingScript = $existingScript;
            $scriptArray = $this->cleanAndDecodeJson($shootingScript->script_json);
        }

        return view('frontoffice.shooting_script_result', compact('session', 'contentIdea', 'shootingScript', 'scriptArray'));
    }

    protected function generateShootingScriptPrompt($contentIdea, $diagnosis, $swot, $gayaPembawaan, $targetDurasi, $penyebutanAudiens)
    {
        // Perbaiki handling script_poin_utama
        $poinUtama = [];

        if (is_array($contentIdea->script_poin_utama)) {
            // Jika sudah array (dari cast model)
            $poinUtama = $contentIdea->script_poin_utama;
        } elseif (is_string($contentIdea->script_poin_utama)) {
            // Jika masih string JSON
            $decoded = json_decode($contentIdea->script_poin_utama, true);
            $poinUtama = is_array($decoded) ? $decoded : [$contentIdea->script_poin_utama];
        } else {
            // Fallback jika null atau tipe lain
            $poinUtama = [];
        }

        // Generate string untuk prompt
        $poinUtamaStr = '';
        if (count($poinUtama) > 0) {
            $poinUtamaStr = implode("\n", array_map(function($poin, $index) {
                return ($index + 1) . ". " . $poin;
            }, $poinUtama, array_keys($poinUtama)));
        }

        // Rest of your method code...
        $diagnosis = $diagnosis ?: '-';
        $swot = $swot ?: '-';

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

    // Method untuk melihat usage statistics
    public function showUsageStats(Request $request)
    {
        // Hapus filter user, ambil semua user
        // $user = auth()->user(); // Hapus ini

        // Convert string ke Carbon object
        $dateFromString = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateToString = $request->input('date_to', now()->format('Y-m-d'));

        $dateFrom = \Carbon\Carbon::parse($dateFromString);
        $dateTo = \Carbon\Carbon::parse($dateToString);

        // Ambil stats dari semua user (null = semua user)
        $stats = $this->geminiService->getUsageStats(null, $dateFrom, $dateTo);

        // Hitung total dari semua user
        $totalStats = [
            'total_requests' => $stats->sum('total_requests'),
            'total_tokens' => $stats->sum('total_tokens'),
            'total_cost_idr' => $stats->sum('total_cost_idr'),
            'avg_response_time' => $stats->avg('avg_response_time_ms')
        ];

        // Ambil data user untuk tabel
        $userStats = $this->geminiService->getUserStats($dateFrom, $dateTo);

        return view('frontoffice.usage_stats', compact('stats', 'totalStats', 'dateFrom', 'dateTo', 'userStats'));
    }

    public function exportUsage(Request $request)
    {
        $user = auth()->user();

        // Convert string ke Carbon object
        $dateFromString = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateToString = $request->input('date_to', now()->format('Y-m-d'));

        $dateFrom = \Carbon\Carbon::parse($dateFromString);
        $dateTo = \Carbon\Carbon::parse($dateToString);

        $usages = \App\Models\ApiUsage::where('user_id', $user->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'gemini_usage_' . $dateFromString . '_to_' . $dateToString . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($usages) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'Session ID',
                'Tahap',
                'Input Tokens',
                'Output Tokens',
                'Total Tokens',
                'Biaya Input (Rp)',
                'Biaya Output (Rp)',
                'Total Biaya (Rp)',
                'Response Time (ms)',
                'Status'
            ]);

            // Data
            foreach ($usages as $usage) {
                fputcsv($file, [
                    $usage->created_at->format('Y-m-d H:i:s'),
                    $usage->session_id,
                    $usage->step,
                    $usage->input_tokens,
                    $usage->output_tokens,
                    $usage->total_tokens,
                    $usage->input_cost_idr,
                    $usage->output_cost_idr,
                    $usage->total_cost_idr,
                    $usage->response_time_ms,
                    $usage->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showAdsForm($session_id)
    {
        return view('frontoffice.ads_form', compact('session_id'));
    }

    public function generateAds(Request $request, $session_id)
    {
        $user = auth()->user();
        $request->validate([
            'platform' => 'required|in:facebook_instagram,tiktok,google_search',
            'goal'     => 'required',
            'product'  => 'required',
            'offer'    => 'nullable',
        ]);

        // Load context dari AI Analysis sebelumnya (diagnosis, SWOT, USP, Persona, dsb)
        $session = UserSession::findOrFail($session_id);

        $diagnosis = AiResponse::where('user_session_id', $session_id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $session_id)->where('step', 'swot')->first();
        $contentPlan = AiResponse::where('user_session_id', $session_id)->where('step', 'content_plan')->first();

        // Generate prompt sesuai platform
        $prompt = $this->generateAdsPrompt(
            $request->platform,
            $request->goal,
            $request->product,
            $request->offer,
            !empty($diagnosis->profil_dna_bisnis) ? $diagnosis->profil_dna_bisnis : ($diagnosis ? $diagnosis->ai_response : ''),
            $swot ? $swot->ai_response : ''
        );

        // Request ke Gemini (gunakan service-mu sendiri, sesuai yang dipakai untuk step lain)
        $result = $this->geminiService->generateContent(
            $prompt,
            $user->id,
            $session_id,
            'ads'
        );

        // Simpan ke DB
        $adsResult = AdsResult::create([
            'user_id'           => $user->id,
            'user_session_id'   => $session_id,
            'platform'          => $request->platform,
            'goal'              => $request->goal,
            'product'           => $request->product,
            'offer'             => $request->offer,
            'prompt'            => $prompt,
            'ai_response'       => $result['content'],
            'tokens_used'       => $result['usage']['total_tokens'] ?? null,
            'cost_idr'          => $result['usage']['total_cost_idr'] ?? null,
            'response_time_ms'  => $result['usage']['response_time_ms'] ?? null,
        ]);

        // Tampilkan hasil
        return view('frontoffice.ads_result', [
            'adsResult' => $adsResult,
            'session'   => $session,
        ]);
    }

    protected function generateAdsPrompt($platform, $goal, $product, $offer, $diagnosis, $swot)
    {
        if ($platform === 'google_search') {
            return <<<EOP
# PERAN
Kamu adalah seorang Google Ads Specialist dan Direct-Response Copywriter. Keahlian utamamu adalah riset keyword berbasis niat pencarian (search intent) dan merangkai kata-kata yang memaksimalkan Click-Through Rate (CTR) dan konversi dalam batasan karakter yang ketat.

# KONTEKS
Kamu akan merancang sebuah kampanye Google Search Ads untuk sebuah bisnis. Kamu memiliki akses penuh ke data fundamental bisnis dan detail tujuan kampanye.

**BAGIAN A: ASET BISNIS & MARKETING (Data Tersimpan)**
* Profil Bisnis Lengkap: $diagnosis
* Analisis SWOT: $swot

**BAGIAN B: INFORMASI KAMPANYE IKLAN (Input Baru dari Pengguna)**
* **Tujuan Utama Iklan:** $goal
* **Produk/Layanan yang Dipromosikan:** $product
* **Penawaran Spesial (Jika Ada):** $offer

# TUGAS
Berdasarkan PERAN dan KESELURUHAN KONTEKS di atas, rancang sebuah **"Struktur Kampanye Google Ads"** yang solid. Rencana ini harus mencakup:

1.  **Riset Keyword dan Grup Iklan (Ad Group):**
    * Sarankan **2 hingga 3 Grup Iklan** yang berbeda berdasarkan tema atau intensi pencarian yang spesifik (contoh: Grup 'Jasa', Grup 'Harga', Grup 'Kompetitor').
    * Untuk setiap grup, berikan **5-7 contoh keyword** yang sangat relevan.
    * Untuk setiap keyword, sarankan **Jenis Pencocokan (Match Type)** yang paling efektif (pilih antara: `Phrase` atau `Exact`) dan berikan **alasan singkat** mengapa jenis itu dipilih untuk memaksimalkan relevansi dan budget.

2.  **Pembuatan Naskah Iklan Responsif (Responsive Search Ad):**
    * Untuk **SETIAP** Grup Iklan yang kamu sarankan, buatkan **1 contoh Iklan Responsif** yang optimal.
    * Iklan tersebut harus terdiri dari:
        * **7 contoh Headline** (maksimal 30 karakter per headline). Pastikan headlines relevan dengan tema keyword di grupnya dan mengandung USP.
        * **3 contoh Description** (maksimal 90 karakter per deskripsi). Deskripsi harus menjelaskan detail penawaran dan membangun kepercayaan.

# FORMAT
Sajikan output dalam format terstruktur yang jelas dan mudah dibaca, diorganisir per Grup Iklan.

**### STRUKTUR KAMPANYE GOOGLE ADS ANDA ###**

**## GRUP IKLAN 1: [Nama Grup Iklan, misal: Jasa Pemasangan Profesional] ##**

**1. Rekomendasi Keyword & Jenis Pencocokan:**
* `Keyword`: [Contoh keyword 1], `Jenis`: [Phrase/Exact], `Alasan`: [Alasan singkat]
* `Keyword`: [Contoh keyword 2], `Jenis`: [Phrase/Exact], `Alasan`: [Alasan singkat]
* ... (dan seterusnya)

**2. Contoh Iklan Responsif:**
* **Headlines:**
    * Headline 1: [Contoh headline 1]
    * Headline 2: [Contoh headline 2]
    * ... (dan seterusnya sampai 7)
* **Descriptions:**
    * Description 1: [Contoh deskripsi 1]
    * Description 2: [Contoh deskripsi 2]
    * Description 3: [Contoh deskripsi 3]

**## GRUP IKLAN 2: [Nama Grup Iklan, misal: Harga & Produk YKK AP] ##**
(Ulangi struktur di atas untuk Grup Iklan kedua)

**(Opsional) ## GRUP IKLAN 3: [Nama Grup Iklan lainnya] ##**
(Ulangi struktur di atas untuk Grup Iklan ketiga)
EOP;
        } else {
            return <<<EOP
# PERAN
Kamu adalah seorang Digital Advertising Strategist senior yang bekerja di agensi periklanan ternama. Kamu ahli dalam merumuskan keseluruhan paket kampanye dari A sampai Z: mulai dari naskah (copy), penargetan audiens (targeting), hingga konsep materi kreatif (creative brief).

# KONTEKS
**BAGIAN A: ASET BISNIS & MARKETING (Data Tersimpan)**
* Profil Bisnis Lengkap: $diagnosis
* Analisis SWOT: $swot

**BAGIAN B: INFORMASI KAMPANYE IKLAN (Input Baru dari Pengguna)**
* Platform Iklan: $platform
* Tujuan Utama Iklan: $goal
* Produk/Layanan yang Dipromosikan: $product
* Penawaran Spesial: $offer

# TUGAS
Berdasarkan PERAN dan KESELURUHAN KONTEKS di atas, buatkan sebuah **"Paket Kampanye Iklan"** yang lengkap. Paket ini harus berisi 3 komponen utama untuk **SATU** variasi iklan yang paling direkomendasikan.

1.  **Naskah Iklan (Ad Copy):**
    * Buat **satu** naskah iklan terbaik menggunakan formula AIDA.
    * **Wajib sesuaikan** gaya dan formatnya berdasarkan **Platform Iklan** yang dipilih.

2.  **Rekomendasi Settingan Iklan (Target Audiens):**
    * Berikan rekomendasi penargetan audiens yang spesifik untuk platform yang dipilih.
    * **Wajib** dasarkan rekomendasi minat/perilaku pada data **Persona Pembeli Utama** yang ada di konteks.

3.  **Brief Aset Kreatif (Visual/Video):**
    * Berikan sebuah brief singkat dan jelas untuk materi visual (gambar/pamflet/video) yang akan mendukung naskah iklan.
    * Konsep visual harus selaras dengan naskah iklan dan USP.

# FORMAT
Sajikan output dalam format terstruktur yang jelas. Gunakan field-field berikut:

**### PAKET KAMPANYE IKLAN ANDA ###**

**1. Naskah Iklan (Ad Copy):**
* `Platform`: [Sebutkan platform yang dipilih]
* `Headline_atau_Hook`: [Isi di sini]
* `Body_Copy`: [Isi di sini]
* `Call_to_Action_Suggestion`: [Isi di sini]

**2. Rekomendasi Target Audiens:**
* `Lokasi`: [Saran lokasi, misal: Surabaya & Sidoarjo (radius +25km)]
* `Umur`: [Saran rentang umur, misal: 30 - 55 tahun]
* `Gender`: [Saran gender, misal: Semua]
* `Minat_dan_Perilaku_Detail`: [Saran minat/perilaku berdasarkan Persona. WAJIB sebutkan 3-5 minat, misal: "Real Estate Investing", "Arsitektur", "Pengunjung Pameran Properti", "Mengikuti akun Developer Properti X & Y"]

**3. Brief Aset Kreatif:**
* `Jenis_Aset`: [Saran jenis, misal: Video singkat 15 detik atau Carousel 3-slide]
* `Konsep_Utama`: [Ide besar dalam 1 kalimat. Misal: "Menampilkan proses pemasangan kusen yang cepat dan rapi oleh tim profesional kami."]
* `Visual_yang_Ditampilkan`: [Deskripsi visual. Misal: "Slide 1: Foto 'before' kusen kayu yang lapuk. Slide 2: Video timelapse proses pemasangan kusen aluminium. Slide 3: Foto 'after' hasil akhir yang elegan dan modern."]
* `Teks_Overlay_di_Visual`: [Teks singkat yang muncul di video/gambar. Misal: "Ganti Kusen Tanpa Ribet!", "Garansi 10 Tahun"]
* `Suasana_Mood`: [Mood yang ingin diciptakan. Misal: Profesional, Terpercaya, dan Memuaskan]
EOP;
        }
    }


}
