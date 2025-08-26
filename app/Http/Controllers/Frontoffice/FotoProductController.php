<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageGenerator\ImageGenService;

class FotoProductController extends Controller
{
    public function index()
    {
        return view('frontoffice.generator.foto-product', [
            'generatedPrompt' => null,
            'imageUrl'        => null,
            'inputs'          => [],
            'error'           => null,
        ]);
    }

    public function generate(Request $request, ImageGenService $imageGen)
    {
        // Semua opsional mengikuti UI React aslinya
        $validated = $request->validate([
            'productName'       => ['nullable', 'string', 'max:200'],
            'brandName'         => ['nullable', 'string', 'max:200'],
            'headline'          => ['nullable', 'string', 'max:200'],
            'fontFamily'        => ['nullable', 'string', 'max:200'],
            'visualStyle'       => ['nullable', 'string', 'max:300'],
            'background'        => ['nullable', 'string', 'max:300'],
            'lighting'          => ['nullable', 'string', 'max:100'],
            'composition'       => ['nullable', 'string', 'max:100'],
            'props'             => ['nullable', 'string', 'max:300'],
            'mood'              => ['nullable', 'string', 'max:120'],
            'branding'          => ['nullable', 'string', 'max:300'],
            'orientation'       => ['nullable', 'string', 'max:50'], // "Square 1:1", "Vertical 9:16", dll.
            'additionalDetails' => ['nullable', 'string', 'max:500'],
        ]);

        // ====== Rakit prompt (meniru generatePrompt() di React) ======
        $referenceInstruction = "create a new commercial photograph. ";

        $productName = $validated['productName'] ?? '';
        $brandName   = $validated['brandName'] ?? '';
        $headline    = $validated['headline'] ?? '';
        $fontFamily  = $validated['fontFamily'] ?? 'sans-serif';
        $visualStyle = $validated['visualStyle'] ?? '';
        $background  = $validated['background'] ?? '';
        $lighting    = $validated['lighting'] ?? '';
        $composition = $validated['composition'] ?? '';
        $props       = $validated['props'] ?? '';
        $mood        = $validated['mood'] ?? '';
        $branding    = $validated['branding'] ?? '';
        $orientation = $validated['orientation'] ?? '';
        $additional  = $validated['additionalDetails'] ?? '';

        // Nama font "ramah-baca" untuk prompt (mirip React)
        $fontMap = [
            "sans-serif"        => "a standard sans-serif",
            "'Roboto', sans-serif" => "Roboto",
            "'Poppins', sans-serif" => "Poppins",
            "'Lato', sans-serif" => "Lato",
            "'Montserrat', sans-serif" => "Montserrat",
            "'Oswald', sans-serif" => "Oswald",
            "'Raleway', sans-serif" => "Raleway",
            "'Playfair Display', serif" => "Playfair Display",
            "'Merriweather', serif" => "Merriweather",
            "'Lobster', cursive" => "Lobster",
            "'Pacifico', cursive" => "Pacifico",
            "'Bebas Neue', cursive" => "Bebas Neue",
        ];
        $selectedFontName = $fontMap[$fontFamily] ?? 'a standard sans-serif';

        $subjectPart = 'The subject is a "' . ($productName ?: 'product') . '"'
            . ($brandName ? ' from the brand "' . $brandName . '"' : '');

        $parts = array_filter([
            $subjectPart,
            $headline ? 'The image should subtly feature the headline text: "' . $headline . '"' : null,
            ($headline && $fontFamily !== 'sans-serif') ? 'The headline font should have a style similar to ' . $selectedFontName : null,
            'The visual style should be ' . ($visualStyle ?: 'photorealistic'),
            $background ? 'with a ' . $background . ' background' : null,
            $lighting ? 'lit by ' . $lighting : null,
            $composition ? 'using a ' . $composition . ' composition' : null,
            $props ? 'accompanied by supporting props such as ' . $props : null,
            $mood ? 'evoking a ' . $mood . ' mood' : null,
            $branding ?: null,
            $orientation ? 'The image must have a ' . $orientation . ' aspect ratio' : null,
            $additional ? 'Incorporate these specific details: ' . $additional : null,
        ]);

        $generatedPrompt = rtrim(
                $referenceInstruction . implode('. ', $parts),
                ". "
            ) . '.';
        // 1) Map orientasi → RASIO (bukan size)
        $ratioMap = [
            'Square 1:1'        => '1:1',
            'Vertical 9:16'     => '9:16',
            'Vertical 4:5'      => '4:5',      // nanti dibulatkan ke 1024x1792
            'Horizontal 16:9'   => '16:9',
            'Horizontal 1.91:1' => '16:9',     // aman → bulatkan ke 1792x1024
        ];
        $ratio = $ratioMap[$orientation] ?? '1:1';

        try {
            if ($ratio === '1:1') {
                // Square: tetap pakai service lama (stabil)
                $imageUrl = $imageGen->generate($generatedPrompt, '1:1');
            } else {
                // Non-square: panggil Sumopod langsung dengan payload minimal
                $imageUrl = $this->sumopodDirectMinimal($generatedPrompt, $ratio);
                if (!$imageUrl) {
                    // fallback terakhir: coba square supaya user tetap dapat hasil
                    $imageUrl = $imageGen->generate($generatedPrompt, '1:1');
                }
            }

            return view('frontoffice.generator.foto-product', [
                'generatedPrompt' => $generatedPrompt,
                'imageUrl'        => $imageUrl,
                'inputs'          => $validated,
                'error'           => null,
            ]);

        } catch (\Throwable $e) {
            return view('frontoffice.generator.foto-product', [
                'generatedPrompt' => $generatedPrompt,
                'imageUrl'        => null,
                'inputs'          => $validated,
                'error'           => $e->getMessage(),
            ]);
        }
    }

    private function sumopodDirectMinimal(string $prompt, string $ratio): ?string
    {
        $apiKey  = env('SUMOPOD_API_KEY');
        $baseUrl = rtrim(env('SUMOPOD_BASE_URL', 'https://ai.sumopod.com/v1'), '/');

        if (!$apiKey) return null;

        // Hanya tiga size resmi yg biasanya diterima gateway OpenAI:
        $r = preg_replace('/\s+/', '', $ratio);
        $size = match ($r) {
            '9:16', '4:5'      => '1024x1792', // portrait resmi
            '16:9', '1.91:1'   => '1792x1024', // landscape resmi
            default            => '1024x1024',
        };

        try {
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
                ->connectTimeout((int) env('SUMOPOD_CONNECT_TIMEOUT', 15))
                ->timeout(60) // sedikit lebih longgar utk non-square
                ->post($baseUrl . '/images/generations', [
                    'model'  => 'gpt-image-1',
                    'prompt' => $prompt,
                    'size'   => $size,
                    'n'      => 1,
                    // TANPA 'quality', TANPA 'response_format'
                ]);

            if (!$resp->ok()) {
                \Log::warning('sumopodDirectMinimal non-OK', [
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

                $filename = 'foto-product/fp_' . Str::uuid()->toString() . '.png';
                Storage::disk('public')->put($filename, $bin);
                return asset('storage/' . $filename);
            }

            return null;

        } catch (\Throwable $e) {
            \Log::warning('sumopodDirectMinimal exception', ['m' => $e->getMessage()]);
            return null;
        }
    }
}
