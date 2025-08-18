<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laundry', function (Blueprint $table) {
            $table->id();

            // Tanggal permintaan laundry
            $table->date('tanggal');

            // Foreign key ke pasien
            $table->unsignedBigInteger('pasien_id');

            // Kolom lain
            $table->string('nomr'); // nomor rekam medis pasien
            $table->float('berat')->nullable();
            $table->decimal('biaya', 10, 3)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('siap_pada')->nullable();

            // Timestamps otomatis
            $table->timestamps();

            // Relasi
            $table->foreign('pasien_id')
                ->references('id')->on('pasien')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry', function (Blueprint $table) {
            $table->dropForeign(['pasien_id']);
        });

        Schema::dropIfExists('laundry');
    }
};
