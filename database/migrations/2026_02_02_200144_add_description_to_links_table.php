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
        // Column description already exists
        // Schema::table('links', function (Blueprint $table) {
        //     if (!Schema::hasColumn('links', 'description')) {
        //         $table->text('description')->nullable()->after('title');
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
