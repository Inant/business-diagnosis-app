<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'step',
        'prompt_length',
        'response_length',
        'input_tokens',
        'output_tokens',
        'total_tokens',
        'input_cost_idr',
        'output_cost_idr',
        'total_cost_idr',
        'response_time_ms',
        'model',
        'status',
        'error_message'
    ];

    protected $casts = [
        'input_cost_idr' => 'decimal:2',
        'output_cost_idr' => 'decimal:2',
        'total_cost_idr' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSession()
    {
        return $this->belongsTo(UserSession::class, 'session_id', 'session_id');
    }
}
