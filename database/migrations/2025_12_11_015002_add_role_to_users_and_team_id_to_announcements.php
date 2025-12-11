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
        // Tahap 1: Reset kolom role di tabel users
        Schema::table('users', function (Blueprint $table) {
            // Hapus jika ada (untuk menghindari bentrok tipe data enum vs string)
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });

        // Buat ulang kolom role
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin_tim')->after('email'); 
        });

        // Tahap 2: Tambahkan team_id ke announcements dengan pengecekan
        Schema::table('announcements', function (Blueprint $table) {
            // Hanya buat jika kolom belum ada
            if (!Schema::hasColumn('announcements', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'team_id')) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
        
        // Kembalikan ke role enum user biasa
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->default('user')->after('email');
        });
    }
};