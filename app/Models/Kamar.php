<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'ruangan_id',
        'nomor_kamar',
    ];

    // Relasi ke tabel ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Relasi ke tabel pasien
    public function pasien()
    {
        return $this->hasMany(Pasien::class);
    }

    // Relasi ke tabel panic_logs
    public function panicLogs()
    {
        return $this->hasMany(PanicLog::class);
    }
}
