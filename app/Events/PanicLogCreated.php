<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PanicLogCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pasien;

    /**
     * Terima objek pasien dengan relasi kamar dan ruangan sudah dimuat.
     */
    public function __construct($pasien)
    {
        // Pastikan relasi kamar dan ruangan sudah dimuat (lazy eager load jika belum)
        $pasien->loadMissing('kamar.ruangan');
        $this->pasien = $pasien;
    }

    /**
     * Channel broadcasting.
     */
    public function broadcastOn()
    {
        return new Channel('panic-logs');
    }

    /**
     * Data yang dikirim ke client via broadcasting.
     */
    public function broadcastWith()
    {
        $latestPanicLog = optional($this->pasien->kamar)->panicLogs()->latest()->first();
        return [
            'pasien' => $this->pasien ? [
                'nama' => $this->pasien->nama,
                'kendala' => $this->pasien->kendala,
                'status' => $this->pasien->status,

            ] : null,
            'kamar' => [
                'id' => optional($this->pasien->kamar)->id,
                'nomor_kamar' => optional($this->pasien->kamar)->nomor_kamar ?? 'Tidak diketahui',
                'status_panic_log' => $latestPanicLog ? $latestPanicLog->status : 'Tidak ada log',
            ],
            'ruangan' => [
                'nama' => optional(optional($this->pasien->kamar)->ruangan)->nama ?? 'Tidak diketahui',
            ],
            'created_at' => now()->toDateTimeString(),
        ];
    }

}
