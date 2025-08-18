<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laundry extends Model
{
    use HasFactory;

    protected $table = 'laundry';

    protected $fillable = [
        'tanggal',
        'pasien_id',
        'id_ruangan',
        'nomr',
        'berat',
        'biaya',
        'keterangan',
        'siap_pada',
    ];

    // Relasi opsional jika Anda butuh
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
