<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';

    protected $fillable = [
        'id',
        'id_pengaduan',
        'tanggal',
        'created_at',
        'updated_at',
    ];

    public function pengaduan(){
        return $this->hasMany(Pengaduan::class, 'id_riwayat');
    }
}
