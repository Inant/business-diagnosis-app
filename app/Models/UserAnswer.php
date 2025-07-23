<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['user_session_id', 'question_id', 'answer'];
    public function question()
    {
        return $this->belongsTo(\App\Models\Question::class, 'question_id');
    }
}
