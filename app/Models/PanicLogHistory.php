<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanicLogHistory extends Model
{
    protected $table = 'panic_log_histories';

    // Disable default timestamps karena menggunakan changed_at sebagai timestamp custom
    public $timestamps = false;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'panic_log_id',
        'status',
        'changed_at',
        'changed_by',
    ];

    // Cast changed_at ke datetime otomatis
    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Relasi ke PanicLog (panic_logs)
     */
    public function panicLog()
    {
        return $this->belongsTo(PanicLog::class, 'panic_log_id');
    }

    /**
     * Relasi ke User yang mengubah status (nullable)
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
