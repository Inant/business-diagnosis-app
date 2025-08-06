<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'content_plan_id',
        'user_session_id',
        'user_id',
        'days',
        'tujuan_pembuatan_konten',
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

    public function contentIdeas()
    {
        return $this->hasMany(ContentIdea::class, 'content_plan_id', 'content_plan_id');
    }

    // Accessor untuk menghitung jumlah konten
    public function getContentCountAttribute()
    {
        return $this->contentIdeas()->count();
    }
}
