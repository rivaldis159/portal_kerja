<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Mitra;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('mitras')) {
            Mitra::where('nama', 'like', '%Keterangan%')
                ->orWhere('nama', 'like', '%Umur dihitung%')
                ->orWhere('nama', 'No ID')
                ->orWhere('nama', '-')
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
