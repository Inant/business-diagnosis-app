<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\AdsPlan;
use App\Models\UserSession;
use App\Models\AiResponse;
use Illuminate\Http\Request;
use App\Services\GeminiApiService; // Import service baru

class AdsController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiApiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    public function adsHistory()
    {
        $user = auth()->user();

        // Ambil sesi utama user
        $mainSession = UserSession::where('user_id', $user->id)->first();

        if (!$mainSession) {
            return redirect()->route('front.form')->with('info', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        // Ambil semua ads plans dari user ini
        $adsPlans = AdsPlan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontoffice.ads_history', compact('mainSession', 'adsPlans'));
    }

    public function showAdsForm()
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

        return view('frontoffice.ads_form', compact('session'));
    }

    public function generateAds(Request $request)
    {
//        return $request;
        $user = auth()->user();

        $request->validate([
            'platform' => 'required|in:facebook_instagram,tiktok,google_search',
            'goal'     => 'required',
            'product'  => 'required',
            'offer'    => 'nullable',
        ]);

        // Ambil sesi utama user
        $session = UserSession::where('user_id', $user->id)->first();
        if (!$session) {
            return redirect()->route('front.form')->with('info', 'Silakan lengkapi analisa bisnis terlebih dahulu');
        }

        // Generate unique identifier untuk ads plan ini
        $adsPlanId = 'ap_' . time() . '_' . $session->id;

        // Load context dari AI Analysis sebelumnya
        $diagnosis = AiResponse::where('user_session_id', $session->id)->where('step', 'diagnosis')->first();
        $swot = AiResponse::where('user_session_id', $session->id)->where('step', 'swot')->first();

        // Generate prompt sesuai platform
        $prompt = $this->generateAdsPrompt(
            $request->platform,
            $request->goal,
            $request->product,
            $request->offer,
            !empty($diagnosis->profil_dna_bisnis) ? $diagnosis->profil_dna_bisnis : ($diagnosis ? $diagnosis->ai_response : ''),
            $swot ? $swot->ai_response : ''
        );

        try {
            // Request ke Gemini
            $result = $this->geminiService->generateContent(
                $prompt,
                $user->id,
                $session->id,
                'ads'
            );

            // Simpan ke table ads_plans
            $adsPlan = AdsPlan::create([
                'ads_plan_id' => $adsPlanId,
                'user_session_id' => $session->id,
                'user_id' => $user->id,
                'platform' => $request->platform,
                'goal' => $request->goal,
                'product' => $request->product,
                'offer' => $request->offer,
                'prompt' => $prompt,
                'ai_response' => $result['content'],
                'tokens_used' => $result['usage']['total_tokens'] ?? 0,
                'cost_idr' => $result['usage']['total_cost_idr'] ?? 0,
                'response_time_ms' => $result['usage']['response_time_ms'] ?? 0,
            ]);

            return redirect()->route('front.ads.detail', $adsPlanId)
                ->with('success', 'Iklan berhasil di-generate!');

        } catch (\Exception $e) {
            return $e->getMessage();
            return back()->withErrors(['error' => 'Gagal generate iklan: ' . $e->getMessage()]);
        }
    }

    public function showAdsDetail($adsPlanId)
    {
        $user = auth()->user();

        $adsPlan = AdsPlan::where('ads_plan_id', $adsPlanId)
            ->where('user_id', $user->id)
            ->with(['userSession'])
            ->firstOrFail();

        // Parse AI response untuk tampilan yang rapi
        $parsedResponse = $this->parseAdsResponse($adsPlan->ai_response, $adsPlan->platform);

        return view('frontoffice.ads_result', compact('adsPlan', 'parsedResponse'));
    }

    protected function parseAdsResponse($response, $platform)
    {
        // Remove extra whitespace and clean up
        $response = trim($response);

        if ($platform === 'google_search') {
            return $this->parseGoogleAdsResponse($response);
        } else {
            return $this->parseSocialMediaAdsResponse($response);
        }
    }

    protected function parseGoogleAdsResponse($response)
    {
        $html = '<div class="space-y-6">';

        // Split by sections
        $sections = preg_split('/##\s*GRUP IKLAN \d+:/', $response);
        $header = array_shift($sections); // Remove header part

        // Parse header section
        if (strpos($header, '### STRUKTUR KAMPANYE GOOGLE ADS ANDA ###') !== false) {
            $html .= '<div class="ads-section bg-white rounded-xl shadow-lg p-6 mb-6">';
            $html .= '<h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">';
            $html .= '<i class="fab fa-google mr-3 text-green-600"></i>Struktur Kampanye Google Ads';
            $html .= '</h2>';
            $html .= '<div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">';
            $html .= '<p class="text-green-700">Kampanye Google Search Ads yang dioptimalkan untuk bisnis Anda</p>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Parse each ad group
        foreach ($sections as $index => $section) {
            if (empty(trim($section))) continue;

            $html .= '<div class="ads-section bg-white rounded-xl shadow-lg overflow-hidden mb-6">';

            // Extract group name
            $lines = explode("\n", trim($section));
            $groupName = trim($lines[0] ?? 'Grup Iklan ' . ($index + 1));

            // Header
            $html .= '<div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">';
            $html .= '<h3 class="text-xl font-bold mb-2">Grup Iklan ' . ($index + 1) . '</h3>';
            $html .= '<p class="text-blue-100">' . htmlspecialchars($groupName) . '</p>';
            $html .= '</div>';

            $html .= '<div class="p-6">';

            // Parse keywords section
            if (strpos($section, 'Rekomendasi Keyword') !== false) {
                $html .= '<div class="mb-6">';
                $html .= '<h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">';
                $html .= '<i class="fas fa-key mr-2 text-yellow-500"></i>Keywords & Match Types';
                $html .= '</h4>';
                $html .= '<div class="bg-yellow-50 rounded-lg p-4">';

                // Extract keywords
                preg_match_all('/\*\s*`Keyword`:\s*([^,]+),\s*`Jenis`:\s*([^,]+),\s*`Alasan`:\s*(.+)/', $section, $matches, PREG_SET_ORDER);

                if (!empty($matches)) {
                    $html .= '<div class="space-y-3">';
                    foreach ($matches as $match) {
                        $keyword = trim($match[1]);
                        $type = trim($match[2]);
                        $reason = trim($match[3]);

                        $html .= '<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white rounded-lg p-3 border">';
                        $html .= '<div class="flex-1">';
                        $html .= '<span class="font-semibold text-gray-800">' . htmlspecialchars($keyword) . '</span>';
                        $html .= '<p class="text-sm text-gray-600 mt-1">' . htmlspecialchars($reason) . '</p>';
                        $html .= '</div>';
                        $html .= '<span class="mt-2 sm:mt-0 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">' . htmlspecialchars($type) . '</span>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }

                $html .= '</div>';
                $html .= '</div>';
            }

            // Parse ads section
            if (strpos($section, 'Contoh Iklan Responsif') !== false) {
                $html .= '<div class="mb-6">';
                $html .= '<h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">';
                $html .= '<i class="fas fa-ad mr-2 text-purple-500"></i>Iklan Responsif';
                $html .= '</h4>';

                // Parse headlines
                if (preg_match('/\*\*Headlines:\*\*(.*?)\*\*Descriptions:\*\*/s', $section, $headlineMatch)) {
                    $html .= '<div class="bg-purple-50 rounded-lg p-4 mb-4">';
                    $html .= '<h5 class="font-semibold text-purple-800 mb-3">Headlines (Max 30 karakter)</h5>';
                    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-2">';

                    preg_match_all('/Headline \d+:\s*(.+)/', $headlineMatch[1], $headlines);
                    foreach ($headlines[1] as $headline) {
                        $headline = trim($headline);
                        $charCount = strlen($headline);
                        $colorClass = $charCount <= 30 ? 'text-green-600' : 'text-red-600';

                        $html .= '<div class="bg-white rounded-lg p-3 border">';
                        $html .= '<p class="font-medium text-gray-800">' . htmlspecialchars($headline) . '</p>';
                        $html .= '<p class="text-xs ' . $colorClass . ' mt-1">' . $charCount . ' karakter</p>';
                        $html .= '</div>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                }

                // Parse descriptions
                if (preg_match('/\*\*Descriptions:\*\*(.*?)(?=\*\*|$)/s', $section, $descMatch)) {
                    $html .= '<div class="bg-green-50 rounded-lg p-4">';
                    $html .= '<h5 class="font-semibold text-green-800 mb-3">Descriptions (Max 90 karakter)</h5>';
                    $html .= '<div class="space-y-2">';

                    preg_match_all('/Description \d+:\s*(.+)/', $descMatch[1], $descriptions);
                    foreach ($descriptions[1] as $description) {
                        $description = trim($description);
                        $charCount = strlen($description);
                        $colorClass = $charCount <= 90 ? 'text-green-600' : 'text-red-600';

                        $html .= '<div class="bg-white rounded-lg p-3 border">';
                        $html .= '<p class="text-gray-800">' . htmlspecialchars($description) . '</p>';
                        $html .= '<p class="text-xs ' . $colorClass . ' mt-1">' . $charCount . ' karakter</p>';
                        $html .= '</div>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    protected function parseSocialMediaAdsResponse($response)
    {
        $html = '<div class="space-y-6">';

        // Check if it contains the structured format
        if (strpos($response, '### PAKET KAMPANYE IKLAN ANDA ###') !== false) {
            $html .= $this->parseStructuredSocialMediaResponse($response);
        } else {
            // Fallback for unstructured response
            $html .= $this->parseUnstructuredResponse($response);
        }

        $html .= '</div>';
        return $html;
    }

    protected function parseStructuredSocialMediaResponse($response)
    {
        $html = '';

        // Header
        $html .= '<div class="ads-section bg-white rounded-xl shadow-lg p-6 mb-6">';
        $html .= '<h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">';
        $html .= '<i class="fas fa-rocket mr-3 text-purple-600"></i>Paket Kampanye Iklan Anda';
        $html .= '</h2>';
        $html .= '<div class="bg-purple-50 border-l-4 border-purple-400 p-4 rounded-r-lg">';
        $html .= '<p class="text-purple-700">Strategi iklan lengkap yang disesuaikan dengan platform dan target audience Anda</p>';
        $html .= '</div>';
        $html .= '</div>';

        // Parse Ad Copy section
        if (preg_match('/\*\*1\. Naskah Iklan \(Ad Copy\):\*\*(.*?)(?=\*\*2\. Rekomendasi Target Audiens:\*\*|$)/s', $response, $adCopyMatch)) {
            $html .= '<div class="ads-section bg-white rounded-xl shadow-lg overflow-hidden mb-6">';
            $html .= '<div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">';
            $html .= '<h3 class="text-xl font-bold mb-2">📝 Naskah Iklan (Ad Copy)</h3>';
            $html .= '<p class="text-pink-100">Copy iklan yang siap digunakan</p>';
            $html .= '</div>';

            $html .= '<div class="p-6">';
            $adCopyContent = $adCopyMatch[1];

            // Parse platform
            if (preg_match('/\*\s*`Platform`:\s*(.+?)(?=\n|\*)/s', $adCopyContent, $platformMatch)) {
                $platform = trim($platformMatch[1]);
                $html .= '<div class="mb-4 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">';
                $html .= '<p class="text-blue-800"><strong>Platform:</strong> ' . htmlspecialchars($platform) . '</p>';
                $html .= '</div>';
            }

            // Parse headline/hook
            if (preg_match('/\*\s*`Headline_atau_Hook`:\s*\*\*(.*?)\*\*/s', $adCopyContent, $headlineMatch)) {
                $headline = trim($headlineMatch[1]);
                $html .= '<div class="mb-6">';
                $html .= '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
                $html .= '<i class="fas fa-bullhorn mr-2 text-orange-500"></i>Headline / Hook';
                $html .= '</h4>';
                $html .= '<div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 border-l-4 border-orange-400">';
                $html .= '<p class="text-orange-800 font-bold text-lg leading-relaxed">' . htmlspecialchars($headline) . '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }

            // Parse body copy
            if (preg_match('/\*\s*`Body_Copy`:\s*(.*?)(?=\*\s*`Call_to_Action_Suggestion`:|$)/s', $adCopyContent, $bodyMatch)) {
                $body = trim($bodyMatch[1]);
                $html .= '<div class="mb-6">';
                $html .= '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
                $html .= '<i class="fas fa-align-left mr-2 text-green-500"></i>Body Copy';
                $html .= '</h4>';

                // Clean up the body text
                $body = $this->cleanMarkdownText($body);

                // Split by AIDA sections
                $aidaSections = $this->parseAIDAStructure($body);

                if (!empty($aidaSections)) {
                    $html .= '<div class="space-y-4">';
                    foreach ($aidaSections as $section) {
                        $html .= '<div class="bg-gray-50 rounded-lg p-4 border-l-4 ' . $section['border'] . '">';
                        $html .= '<div class="flex items-start">';
                        $html .= '<div class="w-8 h-8 rounded-full ' . $section['bg'] . ' flex items-center justify-center mr-3 mt-1 flex-shrink-0">';
                        $html .= '<span class="text-xs font-bold text-white">' . $section['letter'] . '</span>';
                        $html .= '</div>';
                        $html .= '<div class="flex-1">';
                        $html .= '<h5 class="font-semibold text-gray-800 mb-2">' . $section['title'] . '</h5>';
                        $html .= '<div class="text-gray-700 leading-relaxed">' . $section['content'] . '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                } else {
                    // Fallback if AIDA parsing fails
                    $html .= '<div class="bg-gray-50 rounded-lg p-4">';
                    $html .= '<div class="text-gray-700 leading-relaxed">' . nl2br($body) . '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
            }

            // Parse CTA
            if (preg_match('/\*\s*`Call_to_Action_Suggestion`:\s*(.*?)(?=\n\n|\*\*|$)/s', $adCopyContent, $ctaMatch)) {
                $cta = trim($ctaMatch[1]);
                $cta = $this->cleanMarkdownText($cta);

                $html .= '<div class="mb-4">';
                $html .= '<h4 class="font-semibold text-gray-800 mb-3 flex items-center">';
                $html .= '<i class="fas fa-mouse-pointer mr-2 text-red-500"></i>Call to Action';
                $html .= '</h4>';
                $html .= '<div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">';
                $html .= '<div class="text-red-800">' . nl2br($cta) . '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        // Parse Target Audience section
        if (preg_match('/\*\*2\. Rekomendasi Target Audiens:\*\*(.*?)(?=\*\*3\. Brief Aset Kreatif:\*\*|$)/s', $response, $targetMatch)) {
            $html .= '<div class="ads-section bg-white rounded-xl shadow-lg overflow-hidden mb-6">';
            $html .= '<div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">';
            $html .= '<h3 class="text-xl font-bold mb-2">🎯 Target Audiens</h3>';
            $html .= '<p class="text-green-100">Rekomendasi penargetan yang tepat</p>';
            $html .= '</div>';

            $html .= '<div class="p-6">';
            $targetContent = $targetMatch[1];

            // Parse targeting details with better regex
            $targetFields = [
                'Lokasi' => ['icon' => 'map-marker-alt', 'color' => 'blue'],
                'Umur' => ['icon' => 'birthday-cake', 'color' => 'purple'],
                'Gender' => ['icon' => 'venus-mars', 'color' => 'pink'],
                'Minat_dan_Perilaku_Detail' => ['icon' => 'heart', 'color' => 'red']
            ];

            foreach ($targetFields as $field => $config) {
                // Try different patterns for field matching
                $patterns = [
                    '/\*\s*`' . $field . '`:\s*(.*?)(?=\n\*|\*\*|$)/s',
                    '/\*\*' . str_replace('_', ' ', $field) . '\*\*\s*(.*?)(?=\n\*|\*\*|$)/s',
                    '/\*\*' . $field . '\*\*\s*(.*?)(?=\n\*|\*\*|$)/s'
                ];

                $matched = false;
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $targetContent, $fieldMatch)) {
                        $value = trim($fieldMatch[1]);
                        $value = $this->cleanMarkdownText($value);

                        if (!empty($value)) {
                            $html .= '<div class="mb-4 p-4 bg-' . $config['color'] . '-50 rounded-lg border-l-4 border-' . $config['color'] . '-400">';
                            $html .= '<div class="flex items-start">';
                            $html .= '<i class="fas fa-' . $config['icon'] . ' text-' . $config['color'] . '-600 mr-3 mt-1"></i>';
                            $html .= '<div class="flex-1">';
                            $html .= '<h5 class="font-semibold text-gray-800 mb-2">' . str_replace('_', ' ', $field) . '</h5>';

                            // Special handling for interests (numbered list)
                            if ($field === 'Minat_dan_Perilaku_Detail' && preg_match_all('/\d+\.\s*\*\*(.*?)\*\*:\s*(.*?)(?=\n\d+\.|\n\n|$)/s', $value, $interestMatches, PREG_SET_ORDER)) {
                                $html .= '<div class="space-y-3">';
                                foreach ($interestMatches as $interest) {
                                    $title = trim($interest[1]);
                                    $desc = trim($interest[2]);
                                    $html .= '<div class="bg-white rounded-lg p-3 border border-' . $config['color'] . '-200">';
                                    $html .= '<h6 class="font-semibold text-' . $config['color'] . '-700 mb-1">' . htmlspecialchars($title) . '</h6>';
                                    $html .= '<p class="text-gray-600 text-sm">' . htmlspecialchars($desc) . '</p>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                            } else {
                                $html .= '<p class="text-gray-700">' . nl2br(htmlspecialchars($value)) . '</p>';
                            }

                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $matched = true;
                            break;
                        }
                    }
                }
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        // Parse Creative Brief section
        if (preg_match('/\*\*3\. Brief Aset Kreatif:\*\*(.*?)(?=$)/s', $response, $creativeMatch)) {
            $html .= '<div class="ads-section bg-white rounded-xl shadow-lg overflow-hidden mb-6">';
            $html .= '<div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">';
            $html .= '<h3 class="text-xl font-bold mb-2">🎨 Brief Aset Kreatif</h3>';
            $html .= '<p class="text-indigo-100">Panduan untuk membuat visual yang menarik</p>';
            $html .= '</div>';

            $html .= '<div class="p-6">';
            $creativeContent = $creativeMatch[1];

            // Parse creative fields
            $creativeFields = [
                'Jenis_Aset' => ['icon' => 'file-video', 'color' => 'blue'],
                'Konsep_Utama' => ['icon' => 'lightbulb', 'color' => 'yellow'],
                'Visual_yang_Ditampilkan' => ['icon' => 'images', 'color' => 'green'],
                'Teks_Overlay_di_Visual' => ['icon' => 'font', 'color' => 'purple'],
                'Suasana_Mood' => ['icon' => 'smile', 'color' => 'pink']
            ];

            foreach ($creativeFields as $field => $config) {
                // Try different patterns
                $patterns = [
                    '/\*\s*`' . $field . '`:\s*(.*?)(?=\n\*|\*\*|$)/s',
                    '/\*\*' . str_replace('_', ' ', $field) . '\*\*\s*(.*?)(?=\n\*|\*\*|$)/s'
                ];

                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $creativeContent, $fieldMatch)) {
                        $value = trim($fieldMatch[1]);
                        $value = $this->cleanMarkdownText($value);

                        if (!empty($value)) {
                            $html .= '<div class="mb-6">';
                            $html .= '<h5 class="font-semibold text-gray-800 mb-3 flex items-center">';
                            $html .= '<i class="fas fa-' . $config['icon'] . ' mr-2 text-' . $config['color'] . '-600"></i>';
                            $html .= str_replace('_', ' ', $field);
                            $html .= '</h5>';
                            $html .= '<div class="bg-' . $config['color'] . '-50 rounded-lg p-4 border-l-4 border-' . $config['color'] . '-400">';

                            // Special handling for visual descriptions (bullet points)
                            if ($field === 'Visual_yang_Ditampilkan') {
                                $this->parseVisualDescriptions($value, $html);
                            } elseif ($field === 'Teks_Overlay_di_Visual') {
                                $this->parseTextOverlays($value, $html);
                            } else {
                                $html .= '<p class="text-' . $config['color'] . '-800">' . nl2br(htmlspecialchars($value)) . '</p>';
                            }

                            $html .= '</div>';
                            $html .= '</div>';
                            break;
                        }
                    }
                }
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

// Helper method to clean markdown text
    protected function cleanMarkdownText($text)
    {
        // Remove excessive asterisks and markdown formatting
        $text = preg_replace('/\*{3,}/', '', $text);
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/`(.*?)`/', '<code class="bg-gray-200 px-1 rounded">$1</code>', $text);

        // Clean up extra spaces and line breaks
        $text = preg_replace('/\n\s*\n\s*\n/', "\n\n", $text);
        $text = trim($text);

        return $text;
    }

// Helper method to parse AIDA structure
    protected function parseAIDAStructure($body)
    {
        $aidaSections = [];
        $sections = [
            'Attention' => ['title' => 'Attention (Perhatian)', 'letter' => 'A', 'bg' => 'bg-red-500', 'border' => 'border-red-400'],
            'Interest' => ['title' => 'Interest (Minat)', 'letter' => 'I', 'bg' => 'bg-blue-500', 'border' => 'border-blue-400'],
            'Desire' => ['title' => 'Desire (Keinginan)', 'letter' => 'D', 'bg' => 'bg-green-500', 'border' => 'border-green-400'],
            'Action' => ['title' => 'Action (Tindakan)', 'letter' => 'A', 'bg' => 'bg-purple-500', 'border' => 'border-purple-400']
        ];

        foreach ($sections as $key => $config) {
            $pattern = '/\*\(' . $key . '\)\*(.*?)(?=\*\([A-Z]|$)/s';
            if (preg_match($pattern, $body, $match)) {
                $content = trim($match[1]);
                $content = $this->cleanMarkdownText($content);

                if (!empty($content)) {
                    $aidaSections[] = array_merge($config, ['content' => $content]);
                }
            }
        }

        return $aidaSections;
    }

// Helper method to parse visual descriptions
    protected function parseVisualDescriptions($value, &$html)
    {
        // Parse timeline descriptions
        if (preg_match_all('/\*\*(.*?):\*\*\s*(.*?)(?=\n\*\*|\n\n|$)/s', $value, $matches, PREG_SET_ORDER)) {
            $html .= '<div class="space-y-3">';
            foreach ($matches as $match) {
                $title = trim($match[1]);
                $desc = trim($match[2]);
                $desc = $this->cleanMarkdownText($desc);

                $html .= '<div class="bg-white rounded-lg p-3 border border-green-200">';
                $html .= '<h6 class="font-semibold text-green-700 mb-2">' . htmlspecialchars($title) . '</h6>';
                $html .= '<p class="text-gray-600 text-sm">' . nl2br(htmlspecialchars($desc)) . '</p>';
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<p class="text-green-800">' . nl2br(htmlspecialchars($value)) . '</p>';
        }
    }

// Helper method to parse text overlays
    protected function parseTextOverlays($value, &$html)
    {
        // Parse timeline overlays
        if (preg_match_all('/\*\*(.*?):\*\*\s*"(.*?)"/', $value, $matches, PREG_SET_ORDER)) {
            $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
            foreach ($matches as $match) {
                $timing = trim($match[1]);
                $text = trim($match[2]);

                $html .= '<div class="bg-white rounded-lg p-3 border border-purple-200">';
                $html .= '<div class="flex items-center justify-between mb-2">';
                $html .= '<span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded">' . htmlspecialchars($timing) . '</span>';
                $html .= '</div>';
                $html .= '<p class="text-gray-800 font-medium">"' . htmlspecialchars($text) . '"</p>';
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
            $html .= '<p class="text-purple-800">' . nl2br(htmlspecialchars($value)) . '</p>';
        }
    }

    protected function parseUnstructuredResponse($response)
    {
        $html = '<div class="ads-section bg-white rounded-xl shadow-lg p-6">';
        $html .= '<h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">';
        $html .= '<i class="fas fa-ad mr-3 text-blue-600"></i>Strategi Iklan';
        $html .= '</h2>';
        $html .= '<div class="prose prose-lg max-w-none text-gray-700">';
        $html .= nl2br(htmlspecialchars($response));
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    protected function generateAdsPrompt($platform, $goal, $product, $offer, $diagnosis, $swot)
    {
        // Gunakan method yang sama seperti di FormController
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
* `Minat_dan_Perilaku_Detail`: [Saran minat/perilaku berdasarkan Persona. WAJIB sebutkan 3-5 minat, misal: "Real Estate Investing", "Arsitektur", "Pengunjung Pameran Properti", "Developer Properti X & Y"]

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
