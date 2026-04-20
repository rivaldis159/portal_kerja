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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('posisi')->nullable();
            $table->string('status_seleksi')->nullable();
            $table->string('posisi_daftar')->nullable();
            
            $table->text('alamat_detail')->nullable();
            $table->string('alamat_prov', 50)->nullable();
            $table->string('alamat_kab', 50)->nullable();
            $table->string('alamat_kec', 50)->nullable();
            $table->string('alamat_desa', 50)->nullable();

            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('deskripsi_pekerjaan_lain')->nullable();
            
            $table->string('no_telp')->nullable();
            $table->string('sobat_id')->unique()->nullable();
            $table->string('email')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
