<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = ['session_id', 'user_id', 'prompt', 'gemini_response'];

}
