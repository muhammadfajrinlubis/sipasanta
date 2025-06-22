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
        Schema::create('laundry', function (Blueprint $table) {
            $table->id();

            // Kolom untuk tanggal
            $table->date('tanggal');

            // Foreign keys
            $table->unsignedBigInteger('id_pasien'); // Ganti dari id_user
            $table->unsignedBigInteger('id_ruangan');

            // Kolom lainnya
            $table->string('nomr');
            $table->float('berat')->nullable();
            $table->decimal('biaya', 10, 3)->nullable();
            $table->text('keterangan')->nullable();
             $table->timestamp('siap_pada')->nullable();

            // Timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();

            // Menambahkan relasi
            $table->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade'); // ke tabel pasien
            $table->foreign('id_ruangan')->references('id')->on('ruangan')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laundry', function (Blueprint $table) {
            $table->dropForeign(['id_pasien']);
            $table->dropForeign(['id_ruangan']);
        });

        Schema::dropIfExists('laundry');
    }
};
