<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update Users: Tambah kolom Tim
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'team_id')) {
                $table->foreignId('team_id')->nullable()->after('id');
            }
        });

        // Update Links: Tambah kolom VPN dan Pusat
        Schema::table('links', function (Blueprint $table) {
            if (!Schema::hasColumn('links', 'is_vpn_required')) {
                $table->boolean('is_vpn_required')->default(false)->after('url');
            }
            if (!Schema::hasColumn('links', 'is_bps_pusat')) {
                $table->boolean('is_bps_pusat')->default(false)->after('team_id');
            }
        });

        // Buat Tabel Detail Pegawai
        if (!Schema::hasTable('employee_details')) {
            Schema::create('employee_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('nip', 18)->nullable();
                $table->string('nik', 16)->nullable();
                $table->string('pangkat_golongan')->nullable();
                $table->string('jabatan')->nullable();
                $table->string('masa_kerja')->nullable();
                $table->string('pendidikan_terakhir')->nullable();
                $table->string('tempat_lahir')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->text('alamat_tinggal')->nullable();
                $table->string('status_perkawinan')->nullable();
                $table->string('nama_pasangan')->nullable();
                $table->string('nomor_rekening')->nullable();
                $table->string('bank_name')->default('BRI');
                $table->string('email_kantor')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Kosongkan agar aman saat rollback
    }
};