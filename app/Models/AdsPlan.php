<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPlan extends Model
{
    protected $fillable = [
        'ads_plan_id',
        'user_session_id',
        'user_id',
        'platform',
        'goal',
        'product',
        'offer',
        'prompt',
        'ai_response',
        'tokens_used',
        'cost_idr',
        'response_time_ms',
    ];

    protected $casts = [
        'cost_idr' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function userSession()
    {
        return $this->belongsTo(UserSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFormattedCostAttribute()
    {
        return 'Rp ' . number_format($this->cost_idr, 0, ',', '.');
    }

    public function getPlatformLabelAttribute()
    {
        $labels = [
            'facebook_instagram' => 'Facebook & Instagram',
            'tiktok' => 'TikTok',
            'google_search' => 'Google Search'
        ];

        return $labels[$this->platform] ?? $this->platform;
    }
}
