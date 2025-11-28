<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategories'; // FORCE pake table kategories

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function sessions()
    {
        return $this->hasMany(LearningSession::class);
    }
}