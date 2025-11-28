<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    use HasFactory;

    protected $table = 'learning_sessions';

    protected $fillable = [
        'mentor_id',
        'judul',
        'deskripsi',
        'kategori_id',
        'tipe',
        'lokasi_offline',
        'link_meeting',
        'tanggal',
        'jam_mulai',
        'durasi',
        'harga',
        'kuota',
        'terisi',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'decimal:2'
    ];

    // Relationships - FIX SYNTAX
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function registrations()
    {
        return $this->hasMany(SessionRegistration::class, 'session_id');
    }

    public function isOnline()
    {
        return $this->tipe === 'online';
    }

    public function isOffline()
    {
        return $this->tipe === 'offline';
    }

    public function isFull()
    {
        return $this->terisi >= $this->kuota;
    }
}