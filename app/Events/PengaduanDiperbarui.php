<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PengaduanDiperbarui implements ShouldBroadcast
{
    use SerializesModels;

    public $petugasId;
    public $pengaduan; // simpan seluruh objek pengaduan

    public function __construct($petugasId, $pengaduan)
    {
        $this->petugasId = $petugasId;
        $this->pengaduan = $pengaduan;
    }

    // Channel khusus untuk petugas ini
    public function broadcastOn()
    {
        return new Channel('pengaduan-updated.' . $this->petugasId);
    }

    public function broadcastAs()
    {
        return 'pengaduan-diperbarui';
    }

   public function broadcastWith()
{
    return [
        'id' => $this->pengaduan->id,
        'deskripsi' => $this->pengaduan->deskripsi,
        // Pastikan tgl_pengaduan adalah objek Carbon
        'tgl_pengaduan' => Carbon::parse($this->pengaduan->tgl_pengaduan)->format('Y-m-d H:i:s'),
    ];
}
}
