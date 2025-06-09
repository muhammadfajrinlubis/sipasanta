<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'id_ruangan',
        'id_sarana',
        'tgl_pengaduan',
        'deskripsi',
        'foto',
        'id_petugas',
        'id_user',
        'status',
        'tipe',
        'tgl_pukul_selesai', // tambahkan jika diperlukan
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    // Jika 'created_at' dan 'updated_at' ada dalam tabel
    public $timestamps = true;

    // Relasi ke tabel 'ruangan'
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    // Relasi ke tabel 'sarana'
    public function sarana()
    {
        return $this->belongsTo(Sarana::class, 'id_sarana');
    }

    // Relasi ke user sebagai petugas
    public function userPetugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    // Relasi ke user sebagai pengadu
    public function userPengadu()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function petugas()
    {
        return $this->belongsToMany(User::class, 'pengaduan_petugas', 'pengaduan_id', 'petugas_id');
    }

    public function riwayat()
    {
        return $this->belongsTo(Riwayat::class, 'id_riwayat');
    }
    public function rating()
    {
        return $this->hasOne(Rating::class, 'id_pengaduan');
    }
}
