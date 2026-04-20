<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->index(['user_id', 'accessed_at'], 'idx_access_logs_user_date');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->index(['team_id', 'is_active'], 'idx_links_team_active');
        });
    }

    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropIndex('idx_access_logs_user_date');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('idx_links_team_active');
        });
    }
};