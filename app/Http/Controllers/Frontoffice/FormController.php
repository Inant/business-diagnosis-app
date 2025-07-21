<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\UserAnswer;
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

        // Update session
        $session->update([
            'prompt' => $prompt,
            'gemini_response' => $geminiResponse,
        ]);

        return redirect()->route('front.result', ['session' => $session->id]);
    }

    public function showResult($session_id)
    {
        $session = UserSession::findOrFail($session_id);
        return view('frontoffice.result', compact('session'));
    }

    protected function generatePrompt($answers)
    {
        $questions = Question::whereIn('id', array_keys($answers))->orderBy('order')->get();

        // Pastikan urut dan semua terisi, jika kosong isi string kosong
        $contents = [];
        foreach ($questions as $q) {
            $contents[$q->order] = "- **{$q->title}:**\n    {$answers[$q->id]}";
        }

        // Jika jumlah pertanyaan tetap, isi kosong untuk yang tidak dijawab
        $max = 8; // Misal: 8 pertanyaan utama
        $list = [];
        for ($i = 1; $i <= $max; $i++) {
            $list[] = $contents[$i] ?? '';
        }

        $contentString = implode("\n", $list);

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

# FORMAT & GAYA
* Gunakan heading (###) untuk setiap bagian: ### Diagnosis Awal, ### Masalah Inti Anda, dan ### Sebuah Pertanyaan untuk Anda.
* Gaya bahasa harus lugas, profesional, dan to the point. Hindari basa-basi.
* **PENTING:** Jangan berikan solusi atau rekomendasi marketing apa pun di tahap ini. Fokus 100% pada diagnosis masalah.
EOP;

        return $prompt;
    }


    protected function sendToGemini($prompt)
    {
        $apiKey = env('GEMINI_API_KEY');
            $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

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

}
