<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'ruangan_id',
        'kamar_id',
        'kendala',
        'status',
    ];

    // Relasi ke tabel ruangan (many-to-one)
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    // Relasi ke tabel kamar (many-to-one)
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
       public function panicLogs()
    {
        return $this->hasMany(PanicLog::class);
    }


}
