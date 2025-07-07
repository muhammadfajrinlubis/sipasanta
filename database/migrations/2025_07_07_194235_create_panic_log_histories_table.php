<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('panic_log_histories', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('panic_log_id'); // relasi ke panic_logs
        $table->enum('status', ['alarm_aktif','belum_ditangani', 'diproses', 'selesai']);
        $table->timestamp('changed_at')->useCurrent(); // waktu perubahan status
        $table->unsignedBigInteger('changed_by')->nullable(); // ID user admin/petugas yang mengubah, jika perlu

        $table->foreign('panic_log_id')->references('id')->on('panic_logs')->onDelete('cascade');
        $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panic_log_histories');
    }
};
