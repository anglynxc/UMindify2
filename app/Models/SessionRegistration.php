<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'status'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    // Relationships - FIX SYNTAX
    public function session()
    {
        return $this->belongsTo(LearningSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}