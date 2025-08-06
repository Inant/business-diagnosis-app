<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentIdea extends Model
{
    protected $fillable = [
        'user_session_id', 'hari_ke', 'judul_konten', 'pilar_konten', 'hook',
        'script_poin_utama', 'call_to_action', 'rekomendasi_format', 'content_plan_id'
    ];
    protected $casts = [
        'script_poin_utama' => 'array',
    ];

    public function shootingScripts()
    {
        return $this->hasMany(ShootingScript::class);
    }

    // Relationships
    public function userSession()
    {
        return $this->belongsTo(UserSession::class);
    }

    public function contentPlan()
    {
        return $this->belongsTo(ContentPlan::class, 'content_plan_id', 'content_plan_id');
    }
}
