<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResponse extends Model
{
    protected $fillable = ['user_session_id', 'step', 'prompt', 'ai_response', 'ai_response_json'];

}
