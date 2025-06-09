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
       Schema::create('pasien', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_rm')->unique(); // Nomor Rekam Medis
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']); // L = Laki-laki, P = Perempuan
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();

            // Relasi ke tabel ruangan
            $table->unsignedBigInteger('ruangan_id');
            $table->foreign('ruangan_id')->references('id')->on('ruangan')->onDelete('cascade');

            // Relasi ke tabel kamar, nullable dan onDelete set null
            $table->unsignedBigInteger('kamar_id')->nullable();
            $table->foreign('kamar_id')->references('id')->on('kamar')->onDelete('set null');

            $table->text('kendala')->nullable();
            $table->enum('status', ['rawat', 'pulang'])->default('rawat');

            $table->timestamps(); // otomatis buat created_at & updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
