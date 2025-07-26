<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiUsage;

class GeminiApiService
{
    private $apiKey;
    private $baseUrl;

    // Harga per 1M token (dalam USD kemudian dikonversi ke IDR)
    private $usdToIdr = 16360;
    private $inputTokenPriceUsd = 0.10;   // $0.10 per 1M tokens
    private $outputTokenPriceUsd = 0.40;  // $0.40 per 1M tokens

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash';
        $this->usdToIdr = env('USD_TO_IDR_RATE', 15800);
    }

    public function generateContent($prompt, $userId = null, $sessionId = null, $step = null)
    {
        $url = $this->baseUrl . ':generateContent?key=' . $this->apiKey;

        $body = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
            ]
        ];

        $startTime = microtime(true);
        $response = Http::timeout(120)->post($url, $body);
        $endTime = microtime(true);

        $responseTime = round(($endTime - $startTime) * 1000, 2);

        if ($response->ok()) {
            $data = $response->json();
            $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada respon dari Gemini.';

            $usageMetadata = $data['usageMetadata'] ?? null;

            $inputTokens = $usageMetadata['promptTokenCount'] ?? $this->estimateTokens($prompt);
            $outputTokens = $usageMetadata['candidatesTokenCount'] ?? $this->estimateTokens($generatedText);
            $totalTokens = $usageMetadata['totalTokenCount'] ?? ($inputTokens + $outputTokens);

            // Hitung biaya dengan harga yang benar
            $inputCostUsd = ($inputTokens / 1000000) * $this->inputTokenPriceUsd;
            $outputCostUsd = ($outputTokens / 1000000) * $this->outputTokenPriceUsd;

            $inputCostIdr = $inputCostUsd * $this->usdToIdr;
            $outputCostIdr = $outputCostUsd * $this->usdToIdr;
            $totalCostIdr = $inputCostIdr + $outputCostIdr;

            // Log ke ApiUsage table
            $this->logApiUsage([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'step' => $step,
                'prompt_length' => strlen($prompt),
                'response_length' => strlen($generatedText),
                'input_tokens' => $inputTokens,
                'output_tokens' => $outputTokens,
                'total_tokens' => $totalTokens,
                'input_cost_idr' => round($inputCostIdr, 4),
                'output_cost_idr' => round($outputCostIdr, 4),
                'total_cost_idr' => round($totalCostIdr, 4),
                'response_time_ms' => $responseTime,
                'model' => 'gemini-2.0-flash',
                'status' => 'success'
            ]);

            return [
                'content' => $generatedText,
                'usage' => [
                    'input_tokens' => $inputTokens,
                    'output_tokens' => $outputTokens,
                    'total_tokens' => $totalTokens,
                    'total_cost_idr' => round($totalCostIdr, 4),
                    'response_time_ms' => $responseTime
                ]
            ];

        } else {
            $this->logApiUsage([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'step' => $step,
                'prompt_length' => strlen($prompt),
                'response_length' => 0,
                'input_tokens' => $this->estimateTokens($prompt),
                'output_tokens' => 0,
                'total_tokens' => $this->estimateTokens($prompt),
                'input_cost_idr' => 0,
                'output_cost_idr' => 0,
                'total_cost_idr' => 0,
                'response_time_ms' => $responseTime,
                'model' => 'gemini-2.0-flash',
                'status' => 'error',
                'error_message' => $response->body()
            ]);

            throw new \Exception("Gemini API Error: " . $response->body());
        }
    }

    private function estimateTokens($text)
    {
        return max(1, intval(strlen($text) / 4));
    }

    private function logApiUsage($data)
    {
        try {
            ApiUsage::create($data);
            Log::info('Gemini API Usage logged', $data);
        } catch (\Exception $e) {
            Log::error('Failed to log API usage: ' . $e->getMessage());
        }
    }

    public function getUsageStats($userId = null, $dateFrom = null, $dateTo = null)
    {
        $query = ApiUsage::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        return $query->selectRaw('
            COUNT(*) as total_requests,
            SUM(input_tokens) as total_input_tokens,
            SUM(output_tokens) as total_output_tokens,
            SUM(total_tokens) as total_tokens,
            SUM(total_cost_idr) as total_cost_idr,
            AVG(response_time_ms) as avg_response_time_ms,
            step,
            DATE(created_at) as date
        ')
            ->groupBy('step', 'date')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getUserStats($dateFrom = null, $dateTo = null)
    {
        $query = ApiUsage::with('user');

        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        return $query->selectRaw('
        user_id,
        COUNT(*) as total_requests,
        SUM(input_tokens) as total_input_tokens,
        SUM(output_tokens) as total_output_tokens,
        SUM(total_tokens) as total_tokens,
        SUM(total_cost_idr) as total_cost_idr,
        AVG(response_time_ms) as avg_response_time_ms
    ')
            ->groupBy('user_id')
            ->orderBy('total_cost_idr', 'desc')
            ->get();
    }
}
