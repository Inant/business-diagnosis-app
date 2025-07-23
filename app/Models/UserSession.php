<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = ['session_id', 'user_id', 'prompt', 'gemini_response'];

    public function aiResponses()
    {
        return $this->hasMany(\App\Models\AiResponse::class, 'user_session_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(\App\Models\UserAnswer::class, 'user_session_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
