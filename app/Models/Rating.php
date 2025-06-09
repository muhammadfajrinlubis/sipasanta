<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating_petugas';

    protected $fillable = [
        'id',
        'id_pengaduan',
        'id_petugas',
        'nilai_rating',
        'komentar',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;

    // Relasi ke 'pengaduan'
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_rating');
    }
}
