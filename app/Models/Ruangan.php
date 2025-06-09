<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $fillable = ['kode', 'nama'];

    public function panicLogs()
    {
        return $this->hasMany(PanicLog::class);
    }

    public function pasiens()
    {
        return $this->hasMany(Pasien::class, 'ruangan_id', 'id');
    }
    public function kamars()
{
    return $this->hasMany(Kamar::class);
}

}

