<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();

            // Menambahkan kolom foreign key
            $table->timestamp('tgl_pengaduan');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('id_sarana');
            $table->text('deskripsi');
            $table->string('foto');
            $table->string('bukti_petugas')->nullable();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->string('status');
            $table->string('alasan')->nullable();
            $table->string('tipe');
            $table->timestamp('tgl_pukul_selesai')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();

            // Menambahkan relasi
            $table->foreign('id_ruangan')->references('id')->on('ruangan')->onDelete('cascade');
            $table->foreign('id_sarana')->references('id')->on('sarana')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id')->on('petugas')->onDelete('set null');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            // Menghapus foreign key sebelum menghapus tabel
            $table->dropForeign(['id_ruangan']);
            $table->dropForeign(['id_sarana']);
            $table->dropForeign(['id_petugas']);
            $table->dropForeign(['id_user']);
        });

        Schema::dropIfExists('pengaduan');
    }
};
