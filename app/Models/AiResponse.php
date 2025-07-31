<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResponse extends Model
{
    protected $fillable = ['user_session_id', 'step', 'prompt', 'ai_response', 'profil_dna_bisnis', 'ai_response_json', 'tujuan_pembuatan_konten', 'tokens_used', 'cost_idr', 'response_time_ms'];

}
