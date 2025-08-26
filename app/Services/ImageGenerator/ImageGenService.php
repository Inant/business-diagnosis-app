<?php

namespace App\Services\ImageGenerator;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageGenService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected bool $verifySsl;
    protected int $budgetSec;          // total budget waktu untuk semua attempt
    protected int $connectTimeoutSec;  // connect timeout per attempt

    public function __construct()
    {
        $this->apiKey  = env('SUMOPOD_API_KEY'); // WAJIB di .env
        $base = rtrim(env('SUMOPOD_BASE_URL', 'https://ai.sumopod.com/v1'), '/');
        $this->baseUrl = preg_replace('~/chat/completions$~', '', $base);

        $this->verifySsl         = filter_var(env('SUMOPOD_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);
        $this->budgetSec         = (int) env('SUMOPOD_BUDGET_TIMEOUT', $this->suggestBudget()); // total utk seluruh alur
        $this->connectTimeoutSec = (int) env('SUMOPOD_CONNECT_TIMEOUT', 15);
    }

    public function generate(string $prompt, string $ratioOrSize = '1:1'): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('SUMOPOD_API_KEY belum diset di .env');
        }

        $sizePrimary = $this->normalizeSize($ratioOrSize);

        // Bagi budget ke 3 attempt: 55% / 30% / sisa
        $t1 = max(20, (int) round($this->budgetSec * 0.55));
        $t2 = max(15, (int) round($this->budgetSec * 0.30));
        $t3 = max(10, $this->budgetSec - ($t1 + $t2));

        // Attempt #1: quality=high, size=map(ratio)
        if ($url = $this->tryGenerate($prompt, $sizePrimary, 'high', null, $t1)) return $url;

        // Attempt #2: quality=standard, size=map(ratio)
        if ($url = $this->tryGenerate($prompt, $sizePrimary, 'standard', null, $t2)) return $url;

        // Attempt #3: quality=standard, size=1024x1024, response_format=b64_json (lebih cepat)
        if ($url = $this->tryGenerate($prompt, '1024x1024', 'standard', 'b64_json', $t3)) return $url;

        throw new \Exception('Gagal generate image setelah beberapa percobaan (timeout/failure).');
    }

    protected function tryGenerate(
        string $prompt,
        string $size,
        string $quality = 'standard',
        ?string $responseFormat = null,
        int $timeout = 30
    ): ?string {
        $payload = [
            'model'   => 'gpt-image-1',
            'prompt'  => $prompt,
            'size'    => $size,      // 1024x1024 | 1024x1792 | 1792x1024
            'quality' => $quality,   // 'high' | 'standard'
            'n'       => 1,
        ];
        if ($responseFormat === 'b64_json') {
            $payload['response_format'] = 'b64_json';
        }

        try {
            $request = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
                ->timeout($timeout)
                ->connectTimeout($this->connectTimeoutSec)
                ->retry(1, 750, function ($exception, $request) {
                    // Retry untuk timeout (cURL 28) saja
                    return true;
                });

            if ($this->verifySsl === false) {
                $request = $request->withOptions(['verify' => false]); // DEV only
            }

            $response = $request->post($this->baseUrl . '/images/generations', $payload);

            if (!$response->ok()) {
                // 408/429/5xx → biarkan fallback ke attempt berikutnya
                return null;
            }

            $first = $response->json('data.0') ?? [];
            $url   = $first['url']      ?? null;
            $b64   = $first['b64_json'] ?? null;

            if ($url) return $url;

            if ($b64) {
                $binary   = base64_decode($b64);
                $filename = 'generated/' . Str::uuid()->toString() . '.png';
                Storage::disk('public')->put($filename, $binary);
                return asset('storage/' . $filename);
            }

            return null;

        } catch (\Throwable $e) {
            return null; // timeout/connection error → fallback attempt berikutnya
        }
    }

    protected function normalizeSize(string $ratioOrSize): string
    {
        $v = trim($ratioOrSize);
        $allowed = ['1024x1024', '1024x1792', '1792x1024'];
        if (preg_match('/^\d{3,4}x\d{3,4}$/', $v) && in_array($v, $allowed, true)) return $v;

        $v = preg_replace('/\s+/', '', $v);
        if ($v === '1:1')  return '1024x1024';
        if ($v === '9:16') return '1024x1792';
        if ($v === '4:5')  return '1024x1792';
        if ($v === '16:9') return '1792x1024';

        return '1024x1024';
    }

    protected function suggestBudget(): int
    {
        $phpMax = (int) ini_get('max_execution_time'); // 0 = unlimited
        // Sisakan buffer 5 detik dari batas PHP
        if ($phpMax > 0) return max(60, min(175, $phpMax - 5));
        return 175; // default budget 175s jika PHP unlimited
    }

    public function generateFromPrompt(string $prompt, string $size): string
    {
        if (empty($this->apiKey)) {
            throw new \Exception('SUMOPOD_API_KEY belum diset di .env');
        }

        // Normalisasi size supaya selalu valid di gpt-image-1
        $sizePrimary = $this->normalizeSize($size);

        // Bagi budget ke 3 attempt: 55% / 30% / sisa → agresif di attempt pertama
        $t1 = max(18, (int) round($this->budgetSec * 0.55));
        $t2 = max(12, (int) round($this->budgetSec * 0.30));
        $t3 = max(10, $this->budgetSec - ($t1 + $t2));

        // Attempt #1: quality=high, size=map(ratio/size)
        if ($url = $this->tryGenerate($prompt, $sizePrimary, 'high', null, $t1)) {
            return $url;
        }

        // Attempt #2: quality=standard, size=map(ratio/size)
        if ($url = $this->tryGenerate($prompt, $sizePrimary, 'standard', null, $t2)) {
            return $url;
        }

        // Attempt #3: quality=standard, size=1024x1024, response_format=b64_json (lebih cepat & pasti)
        if ($url = $this->tryGenerate($prompt, '1024x1024', 'standard', 'b64_json', $t3)) {
            return $url;
        }

        throw new \Exception('Gagal generate image setelah beberapa percobaan (timeout/failure).');
    }
}
