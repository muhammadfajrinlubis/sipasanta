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
            $table->unsignedBigInteger('kamar_id'); // relasi ke kamar
            $table->enum('status', ['belum_ditangani', 'diproses', 'selesai'])->default('belum_ditangani'); // status panic log
            $table->timestamps();

            // Foreign key ke tabel kamar
            $table->foreign('kamar_id')->references('id')->on('kamar')->onDelete('cascade');
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
