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
EOP;
    }


}
