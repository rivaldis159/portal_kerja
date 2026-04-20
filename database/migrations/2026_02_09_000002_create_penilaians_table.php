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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts')->cascadeOnDelete();
            
            $table->unsignedTinyInteger('q1_kualitas')->comment('Kualitas dan Konsistensi Pekerjaan');
            $table->unsignedTinyInteger('q2_inisiatif')->comment('Inisiatif dan Motivasi');
            $table->unsignedTinyInteger('q3_kerjasama')->comment('Kerjasama Tim');
            $table->unsignedTinyInteger('q4_integritas')->comment('Integritas dan Etika');
            $table->unsignedTinyInteger('q5_keandalan')->comment('Keandalan dan Disiplin');
            
            $table->float('average_score', 3, 2); // e.g. 4.80
            
            $table->boolean('rekomendasi')->default(true);
            $table->text('alasan_rekomendasi')->nullable();
            
            $table->unsignedBigInteger('rated_by')->nullable();
            $table->foreign('rated_by')->references('id')->on('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
