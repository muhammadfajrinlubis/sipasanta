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
        Schema::create('panic_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kamar_id');
            $table->unsignedBigInteger('pasien_id'); // tambahkan ini
            $table->enum('status', ['alarm_aktif','belum_ditangani', 'diproses', 'selesai'])->default('belum_ditangani');
            $table->timestamps();

            // Foreign key ke kamar
            $table->foreign('kamar_id')->references('id')->on('kamar')->onDelete('cascade');

            // Foreign key ke pasien
            $table->foreign('pasien_id')->references('id')->on('pasien')->onDelete('cascade'); // pastikan tabel pasien sudah ada
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panic_logs');
    }
};


