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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
            
            $table->unsignedBigInteger('mitra_id');
            $table->foreign('mitra_id')->references('id')->on('mitras')->cascadeOnDelete();
            
            $table->string('kegiatan');
            $table->text('uraian_tugas')->nullable();
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            $table->integer('volume');
            $table->string('satuan');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('nilai_kontrak');
            
            $table->enum('status', ['offered', 'accepted', 'completed', 'cancelled'])->default('offered');
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
