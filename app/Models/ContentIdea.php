<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentIdea extends Model
{
    protected $fillable = [
        'user_session_id', 'hari_ke', 'judul_konten', 'pilar_konten', 'hook',
        'script_poin_utama', 'call_to_action', 'rekomendasi_format'
    ];
    protected $casts = [
        'script_poin_utama' => 'array',
    ];

    public function shootingScripts()
    {
        return $this->hasMany(ShootingScript::class);
    }
}
