<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarana extends Model
{
    use HasFactory;

    protected $table = 'sarana';

    protected $fillable = [
        'nama',
    ];

    public $timestamps = true;

    // Relasi ke 'pengaduan'
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_sarana');
    }
}
