<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'petugas';


    protected $fillable = [
        'nama',
    ];

    public $timestamps = true;
    public function pengaduan() {
        return $this->belongsToMany(Pengaduan::class, 'pengaduan_petugas', 'petugas_id', 'pengaduan_id');
    }
}
