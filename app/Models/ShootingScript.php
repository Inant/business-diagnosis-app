<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShootingScript extends Model
{
    protected $fillable = [
        'content_idea_id', 'user_session_id', 'gaya_pembawaan', 'target_durasi', 'penyebutan_audiens', 'script_json', 'raw_ai_response', 'tokens_used', 'cost_idr','response_time_ms'
    ];
    protected $casts = [
        'script_json' => 'array',
    ];

    public function contentIdea()
    {
        return $this->belongsTo(ContentIdea::class);
    }
}
