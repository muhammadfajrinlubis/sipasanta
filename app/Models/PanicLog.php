<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicLog extends Model
{
    protected $table = 'panic_logs';
    protected $fillable = ['kamar_id', 'status'];


    public function kamars()
    {
    return $this->hasMany(Kamar::class);
    }
}
