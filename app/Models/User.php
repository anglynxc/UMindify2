<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships - FIXED
    public function mentorProfile()
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function sessions() // FIXED: Hapus named argument
    {
        return $this->hasMany(LearningSession::class, 'mentor_id');
    }

    public function sessionRegistrations()
    {
        return $this->hasMany(SessionRegistration::class);
    }

    // Scope untuk role
    public function scopeMentor($query)
    {
        return $query->where('role', 'mentor');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUser($query)
    {
        return $query->where('role', 'user');
    }

    // Helper methods
    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Cek apakah user memiliki aplikasi mentor yang pending
    public function hasPendingMentorApplication()
    {
        return $this->mentorProfile && $this->mentorProfile->status === 'pending';
    }

    // Cek apakah user adalah mentor yang approved
    public function isApprovedMentor()
    {
        return $this->isMentor() && $this->mentorProfile && $this->mentorProfile->status === 'approved';
    }

    // Cek status user untuk tampilan
    public function getStatusBadgeAttribute()
    {
        if ($this->hasPendingMentorApplication()) {
            return '<span class="badge bg-warning">Pending Mentor</span>';
        } elseif ($this->isApprovedMentor()) {
            return '<span class="badge bg-success">Approved Mentor</span>';
        } elseif ($this->isUser()) {
            return '<span class="badge bg-info">User</span>';
        } elseif ($this->isAdmin()) {
            return '<span class="badge bg-danger">Admin</span>';
        }
        
        return '<span class="badge bg-secondary">Unknown</span>';
    }
}