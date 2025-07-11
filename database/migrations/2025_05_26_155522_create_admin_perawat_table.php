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
        Schema::create('admin_perawat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jabatan'); // Sesuaikan tipe data dengan tabel relasi
            $table->unsignedBigInteger('id_user');
            $table->string('nip');
            $table->string('nama');
            $table->string('no_hp');
            $table->string('status_aktif')->default('Aktif');
            $table->string('foto')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();

              // Definisi kunci asing
              $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade')->onUpdate('cascade');
              $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_perawat');
    }
};
