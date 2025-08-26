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

class GeneratorController extends Controller
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

    function socialMediaPost(Request $request)
    {
        return view('frontoffice.generator.social-media-post');
    }

    function veo3(Request $request)
    {
        return view('frontoffice.generator.veo3');
    }
}
