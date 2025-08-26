<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageGenerator\ImageGenService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SocialMediaImageGeneratorController extends Controller
{
    public function index()
    {
        $generatedPrompt = null;
        $imageUrl = null;

        return view('frontoffice.generator.social-media-image-generator', compact('generatedPrompt', 'imageUrl'));
    }

    /**
     * Terima POST dari form, validasi, rakit prompt di server,
     * panggil ImageGenService stub, lalu kembalikan ke view.
     */
    public function generate(Request $request, ImageGenService $imageGenService)
    {
        $data = $request->validate([
            'isiCampaign'     => ['required', 'string'],
            'jenisFont'       => ['required', 'string'],
            'desainCampaign'  => ['required', 'string'],
            'rasioGambar'     => ['required', 'string'],
            'cta'             => ['nullable', 'string'],
            'jenisFontCta'    => ['nullable', 'string'],
            'warnaBackground' => ['nullable', 'string'],
            'detilTambahan'   => ['nullable', 'string'],
        ]);

        $request->flash();

        // Ambil token rasio sebelum spasi → "1:1 (persegi)" => "1:1"
        $ratioToken = explode(' ', $data['rasioGambar'])[0] ?? $data['rasioGambar'];

        $isiCampaign     = trim($data['isiCampaign']);
        $jenisFont       = $data['jenisFont'];
        $desainCampaign  = $data['desainCampaign'];
        $rasioGambar     = $data['rasioGambar'];
        $cta             = isset($data['cta']) ? trim($data['cta']) : '';
        $jenisFontCta    = $data['jenisFontCta'] ?? '';
        $warnaBackground = isset($data['warnaBackground']) ? trim($data['warnaBackground']) : '';
        $detilTambahan   = isset($data['detilTambahan']) ? trim($data['detilTambahan']) : '';

        // ===== Rakit prompt (logika dari JS dipindahkan ke server) =====
        $prompt  = "Buat sebuah gambar desain poster promosi untuk media sosial dengan kualitas tinggi, photorealistic, dan sangat detail. ";
        $prompt .= "Berikut adalah arahan lengkapnya:\n\n";

        $prompt .= "**1. Tema & Subjek Utama:**\n";
        $prompt .= "- Fokus utama adalah promosi kampanye dengan pesan: \"{$isiCampaign}\".\n";
        $prompt .= "- Gunakan produk dari gambar yang telah diunggah sebelumnya sebagai subjek utama. Posisikan produk ini sebagai pusat perhatian dengan pencahayaan studio yang dramatis dan profesional.\n";

        $prompt .= "\n**2. Gaya & Suasana Visual:**\n";
        $prompt .= "- Gaya visual yang diinginkan adalah **{$desainCampaign}**.\n";
        $prompt .= "- Suasana (mood) harus sesuai dengan gaya tersebut (misalnya, jika elegan, gunakan warna mewah; jika fun, gunakan warna cerah).\n";

        $prompt .= "\n**3. Tipografi:**\n";
        $prompt .= "- Untuk teks utama (headline/campaign), gunakan gaya tipografi yang sesuai dengan deskripsi: **{$jenisFont}**.\n";
        if ($cta !== '') {
            $prompt .= "- Untuk teks Call-to-Action (CTA), gunakan gaya tipografi: **{$jenisFontCta}**.\n";
        }
        $prompt .= "- Pastikan semua teks mudah dibaca, dengan hierarki visual yang jelas.\n";

        $prompt .= "\n**4. Komposisi & Tata Letak:**\n";
        $prompt .= "- Rasio aspek gambar harus **{$rasioGambar}**.\n";
        if ($cta !== '') {
            $prompt .= "- Sertakan teks CTA \"{$cta}\" secara jelas. Posisikan di tempat yang strategis tanpa menutupi produk.\n";
        }
        $prompt .= "- Terapkan prinsip desain seperti rule of thirds untuk komposisi yang seimbang dan menarik secara visual.\n";

        $prompt .= "\n**5. Warna & Pencahayaan:**\n";
        if ($warnaBackground !== '') {
            $prompt .= "- Palet warna dominan harus berpusat pada **{$warnaBackground}** sebagai warna background atau elemen utama. Pastikan teks tetap kontras dan mudah terbaca.\n";
        } else {
            $prompt .= "- Gunakan palet warna yang harmonis dan sesuai dengan gaya {$desainCampaign}.\n";
        }
        $prompt .= "- Pencahayaan harus profesional, menonjolkan detail produk dan menciptakan kedalaman.\n";

        $prompt .= "\n**6. Detail Tambahan & Larangan:**\n";
        if ($detilTambahan !== '') {
            $prompt .= "- Perhatikan detail tambahan berikut: {$detilTambahan}\n";
        } else {
            $prompt .= "- Pastikan tidak ada teks placeholder atau watermark. Semua elemen harus terlihat final dan profesional.\n";
        }

//        $prompt .= "\n**Parameter Akhir (untuk AI):**\n";
//        $prompt .= "--ar {$ratioToken} --style raw --quality high --v 6.0";

        try {
            // Jalur utama: pakai service multi-attempt (tetap aman untuk modul lain)
            $imageUrl = $imageGenService->generate($prompt, '1:1'); // rasio 1:1 sesuai form
        } catch (\Throwable $e) {
            // Fallback: panggil Sumopod langsung dengan payload minimal (tanpa quality/response_format)
            $imageUrl = $this->sumopodDirectMinimal($prompt, '1:1');

            if (!$imageUrl) {
                // Kalau tetap gagal, tampilkan error elegan di view
                return back()
                    ->withInput()
                    ->withErrors(['generator' => $e->getMessage() ?: 'Gagal generate image.']);
            }
        }

        $generatedPrompt = $prompt;

        return view('frontoffice.generator.social-media-image-generator', compact('generatedPrompt', 'imageUrl'));
    }

    public function download(Request $request)
    {
        $encoded = $request->query('u');
        if (!$encoded) {
            abort(400, 'URL gambar tidak ditemukan.');
        }

        // URL asli (sudah di-encode di view)
        $url = urldecode($encoded);

        // 1) Kalau hasil lokal (storage)
        $publicPrefix = rtrim(asset('storage'), '/') . '/';
        if (Str::startsWith($url, $publicPrefix)) {
            $relative = ltrim(Str::after($url, $publicPrefix), '/');
            $fullPath = storage_path('app/public/' . $relative);

            if (!is_file($fullPath)) {
                abort(404, 'File tidak ditemukan.');
            }

            $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
            $name = basename($fullPath);

            return response()->download($fullPath, $name, [
                'Content-Type' => $mime,
            ]);
        }

        // 2) Jika eksternal, stream dan proxy agar bisa diunduh
        $parsed = parse_url($url);
        $scheme = $parsed['scheme'] ?? '';
        $host   = $parsed['host']   ?? '';

        if (!in_array($scheme, ['http', 'https'], true)) {
            abort(400, 'Skema URL tidak valid.');
        }
        // Hindari SSRF ke host lokal
        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            abort(403, 'Host tidak diizinkan.');
        }

        try {
            $resp = Http::withOptions(['stream' => true])
                ->timeout(120)
                ->get($url);

            if (!$resp->ok()) {
                abort($resp->status(), 'Gagal mengambil gambar dari sumber.');
            }

            // Tentukan nama file dari ekstensi URL, default png
            $path = $parsed['path'] ?? '';
            $ext  = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
            // Bersihkan query extension jika ada
            $ext  = preg_replace('/[^a-zA-Z0-9]/', '', $ext) ?: 'png';

            $filename = 'generated-' . now()->format('Ymd-His') . '.' . $ext;
            $contentType = $resp->header('Content-Type') ?: 'image/png';

            $stream = $resp->toPsrResponse()->getBody();

            return response()->streamDownload(function () use ($stream) {
                while (!$stream->eof()) {
                    echo $stream->read(1024 * 64);
                }
            }, $filename, [
                'Content-Type'        => $contentType,
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        } catch (\Throwable $e) {
            abort(500, 'Terjadi kesalahan saat mengunduh gambar.');
        }
    }

    private function sumopodDirectMinimal(string $prompt, string $ratio = '1:1'): ?string
    {
        $apiKey  = env('SUMOPOD_API_KEY');
        $baseUrl = rtrim(env('SUMOPOD_BASE_URL', 'https://ai.sumopod.com/v1'), '/');

        if (!$apiKey) return null;

        // HANYA size resmi OPENAI: 1024x1024 | 1024x1792 | 1792x1024
        $size = '1024x1024'; // karena rasio 1:1

        try {
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
                ->connectTimeout((int) env('SUMOPOD_CONNECT_TIMEOUT', 15))
                ->timeout(45)
                ->post($baseUrl . '/images/generations', [
                    'model'  => 'gpt-image-1',
                    'prompt' => $prompt,
                    'size'   => $size,
                    'n'      => 1,
                    // tanpa 'quality', tanpa 'response_format'
                ]);

            if (!$resp->ok()) {
                \Log::warning('sumopodDirectMinimal non-OK (social media)', [
                    'status' => $resp->status(),
                    'body'   => $resp->body(),
                ]);
                return null;
            }

            $first = $resp->json('data.0') ?? [];
            if (!empty($first['url'])) {
                return $first['url'];
            }

            if (!empty($first['b64_json'])) {
                $bin = base64_decode($first['b64_json']);
                if ($bin === false) return null;

                $filename = 'social-media/img_' . Str::uuid()->toString() . '.png';
                Storage::disk('public')->put($filename, $bin);
                return asset('storage/' . $filename);
            }

            return null;

        } catch (\Throwable $e) {
            \Log::warning('sumopodDirectMinimal exception (social media)', ['m' => $e->getMessage()]);
            return null;
        }
    }
}
