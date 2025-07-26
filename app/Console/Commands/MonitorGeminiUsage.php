<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiUsage;
use App\Models\User;
use Carbon\Carbon;

class MonitorGeminiUsage extends Command
{
    protected $signature = 'gemini:monitor-usage
                            {--user= : Filter by user ID}
                            {--days=7 : Number of days to analyze}
                            {--alert-threshold=50000 : Alert if daily cost exceeds this (in IDR)}';

    protected $description = 'Monitor Gemini API usage and costs';

    public function handle()
    {
        $days = $this->option('days');
        $userId = $this->option('user');
        $alertThreshold = $this->option('alert-threshold');

        $startDate = Carbon::now()->subDays($days);

        $this->info("📊 Gemini API Usage Report - Last {$days} days");
        $this->line(str_repeat('=', 60));

        $query = ApiUsage::where('api_usages.created_at', '>=', $startDate); // Fix: specify table

        if ($userId) {
            $query->where('user_id', $userId);
            $user = User::find($userId);
            $this->info("👤 User: " . ($user ? $user->name : 'Unknown'));
        }

        // Overall Statistics
        $totalStats = $query->selectRaw('
            COUNT(*) as total_requests,
            SUM(total_tokens) as total_tokens,
            SUM(total_cost_idr) as total_cost,
            AVG(response_time_ms) as avg_response_time
        ')->first();

        if (!$totalStats || $totalStats->total_requests == 0) {
            $this->warn("⚠️  No usage data found for the specified period.");
            return 0;
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Requests', number_format($totalStats->total_requests)],
                ['Total Tokens', number_format($totalStats->total_tokens)],
                ['Total Cost', 'Rp ' . number_format($totalStats->total_cost, 0)],
                ['Avg Response Time', number_format($totalStats->avg_response_time, 2) . ' ms'],
                ['Cost per Request', 'Rp ' . number_format($totalStats->total_cost / max($totalStats->total_requests, 1), 2)]
            ]
        );

        // Usage by Step
        $this->line("\n📈 Usage by Step:");
        $stepQuery = ApiUsage::where('api_usages.created_at', '>=', $startDate); // Fix: specify table

        if ($userId) {
            $stepQuery->where('user_id', $userId);
        }

        $stepStats = $stepQuery->selectRaw('
            step,
            COUNT(*) as requests,
            SUM(total_tokens) as tokens,
            SUM(total_cost_idr) as cost
        ')
            ->groupBy('step')
            ->orderBy('cost', 'desc')
            ->get();

        $stepData = [];
        foreach ($stepStats as $stat) {
            $stepData[] = [
                'Step' => ucfirst(str_replace('_', ' ', $stat->step ?? 'unknown')),
                'Requests' => number_format($stat->requests),
                'Tokens' => number_format($stat->tokens),
                'Cost (Rp)' => number_format($stat->cost, 0)
            ];
        }

        if (!empty($stepData)) {
            $this->table(['Step', 'Requests', 'Tokens', 'Cost (Rp)'], $stepData);
        } else {
            $this->line("No step data available.");
        }

        // Daily Usage
        $this->line("\n📅 Daily Usage (Last 7 days):");
        $dailyQuery = ApiUsage::where('api_usages.created_at', '>=', $startDate); // Fix: specify table

        if ($userId) {
            $dailyQuery->where('user_id', $userId);
        }

        $dailyStats = $dailyQuery->selectRaw('
            DATE(api_usages.created_at) as date,
            COUNT(*) as requests,
            SUM(total_cost_idr) as cost
        ')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $dailyData = [];
        foreach ($dailyStats as $stat) {
            $cost = (float) $stat->cost;
            $isAlert = $cost > $alertThreshold;

            $dailyData[] = [
                'Date' => Carbon::parse($stat->date)->format('Y-m-d (D)'),
                'Requests' => number_format($stat->requests),
                'Cost (Rp)' => number_format($cost, 0) . ($isAlert ? ' ⚠️' : ''),
            ];

            if ($isAlert) {
                $this->warn("⚠️  High cost alert for {$stat->date}: Rp " . number_format($cost, 0));
            }
        }

        if (!empty($dailyData)) {
            $this->table(['Date', 'Requests', 'Cost (Rp)'], $dailyData);
        } else {
            $this->line("No daily data available.");
        }

        // Top Users (if not filtering by user) - FIX THE MAIN ISSUE
        if (!$userId) {
            $this->line("\n👥 Top Users by Cost:");
            $userStats = ApiUsage::where('api_usages.created_at', '>=', $startDate) // Fix: specify table
            ->join('users', 'api_usages.user_id', '=', 'users.id')
                ->selectRaw('
                    users.name,
                    users.email,
                    COUNT(*) as requests,
                    SUM(api_usages.total_cost_idr) as cost
                ') // Fix: specify table for total_cost_idr
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderBy('cost', 'desc')
                ->limit(10)
                ->get();

            $userData = [];
            foreach ($userStats as $stat) {
                $userData[] = [
                    'Name' => $stat->name,
                    'Email' => $stat->email,
                    'Requests' => number_format($stat->requests),
                    'Cost (Rp)' => number_format($stat->cost, 0)
                ];
            }

            if (!empty($userData)) {
                $this->table(['Name', 'Email', 'Requests', 'Cost (Rp)'], $userData);
            } else {
                $this->line("No user data available.");
            }
        }

        // Cost Projections
        $avgDailyCost = $totalStats->total_cost / $days;
        $this->line("\n💰 Cost Projections:");
        $this->line("• Daily Average: Rp " . number_format($avgDailyCost, 0));
        $this->line("• Monthly Projection: Rp " . number_format($avgDailyCost * 30, 0));
        $this->line("• Yearly Projection: Rp " . number_format($avgDailyCost * 365, 0));

        if ($avgDailyCost > $alertThreshold) {
            $this->error("⚠️  Daily average cost exceeds threshold!");
        } else {
            $this->info("✅ Daily costs are within acceptable range");
        }

        return 0;
    }
}
