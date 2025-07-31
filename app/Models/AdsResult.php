<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsResult extends Model
{
    protected $fillable = [
        'user_id',
        'user_session_id',
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
}
