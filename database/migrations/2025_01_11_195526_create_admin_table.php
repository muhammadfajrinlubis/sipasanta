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
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jabatan'); // Sesuaikan tipe data dengan tabel relasi
            $table->unsignedBigInteger('id_user');   // Sesuaikan tipe data dengan tabel relasi
            $table->string('nip')->unique();         // Tambahkan unique jika NIP bersifat unik
            $table->string('nama');
            $table->string('no_hp');
            $table->string('status_aktif')->default('Aktif');
            $table->string('foto')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();

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
        Schema::table('admin', function (Blueprint $table) {
            $table->dropForeign(['id_jabatan']);
            $table->dropForeign(['id_user']);
        });
        Schema::dropIfExists('admin');
    }
};
