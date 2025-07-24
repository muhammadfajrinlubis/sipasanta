<?php
namespace App\Models;

use App\Models\PanicLogHistory;
use Illuminate\Database\Eloquent\Model;

class PanicLog extends Model
{
    protected $table = 'panic_logs';
    protected $fillable = ['kamar_id', 'status','pasien_id'];


    public function kamars()
    {
    return $this->hasMany(Kamar::class);
    }
    public function histories()
{
    return $this->hasMany(PanicLogHistory::class);
}
}
