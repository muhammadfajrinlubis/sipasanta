<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'level',
    ];

    public $timestamps = true;

    // Relasi ke pengaduan sebagai petugas
    public function pengaduanPetugas()
    {
        return $this->hasMany(Pengaduan::class, 'id_petugas');
    }

    // Relasi ke pengaduan sebagai pengadu
    public function pengaduanPengadu()
    {
        return $this->hasMany(Pengaduan::class, 'id_user');
    }
    public function isAdmin()
{
    return $this->role === 'admin'; // or use your specific role check
}
}
