<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            // Tambahkan kolom target, defaultnya '_blank' (Tab Baru)
            if (!Schema::hasColumn('links', 'target')) {
                $table->string('target')->default('_blank')->after('url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
};