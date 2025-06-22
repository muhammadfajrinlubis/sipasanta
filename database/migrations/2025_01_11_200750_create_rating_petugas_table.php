<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_petugas', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('id_pengaduan');
            $table->string('id_petugas');

            // Kolom lainnya
            $table->text('komentar')->nullable();
            $table->tinyInteger('nilai_rating')->unsigned()->comment('Rating antara 1-5');

            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
            // Relasi
            $table->foreign('id_pengaduan')->references('id')->on('pengaduan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rating_petugas', function (Blueprint $table) {
            // Menghapus foreign key sebelum tabel dihapus
            $table->dropForeign(['id_pengaduan']);
        });

        Schema::dropIfExists('rating_petugas');
    }
};
