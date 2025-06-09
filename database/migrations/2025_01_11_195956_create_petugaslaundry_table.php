<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('petugaslaundry', function (Blueprint $table) {
            $table->id();

            // Kolom foreign key
            $table->unsignedBigInteger('id_jabatan');
            $table->unsignedBigInteger('id_user');

            // Kolom lainnya
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('no_hp');
            $table->string('status_aktif')->default('Aktif');
            $table->string('foto')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();

            // Menambahkan relasi
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petugaslaundry', function (Blueprint $table) {
            // Menghapus foreign key sebelum tabel dihapus
            $table->dropForeign(['id_jabatan']);
            $table->dropForeign(['id_user']);
        });

        Schema::dropIfExists('petugaslaundry');
    }
};
