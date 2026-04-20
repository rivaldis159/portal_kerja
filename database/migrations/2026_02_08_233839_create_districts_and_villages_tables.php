<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->timestamps();
        });

        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->foreignId('district_id')->nullable()->constrained();
            $table->foreignId('village_id')->nullable()->constrained();
        });

        // Migrate Data
        $mitras = DB::table('mitras')->get();
        foreach ($mitras as $mitra) {
            if ($mitra->alamat_kec) {
                // Find or Create District
                $district = DB::table('districts')->where('name', $mitra->alamat_kec)->first();
                if (!$district) {
                    $districtId = DB::table('districts')->insertGetId([
                        'name' => $mitra->alamat_kec, 
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]);
                } else {
                    $districtId = $district->id;
                }

                // Find or Create Village
                $villageId = null;
                if ($mitra->alamat_desa) {
                    $village = DB::table('villages')
                        ->where('district_id', $districtId)
                        ->where('name', $mitra->alamat_desa)
                        ->first();
                    
                    if (!$village) {
                        $villageId = DB::table('villages')->insertGetId([
                            'district_id' => $districtId,
                            'name' => $mitra->alamat_desa,
                            'created_at' => now(), 
                            'updated_at' => now()
                        ]);
                    } else {
                        $villageId = $village->id;
                    }
                }
                
                // Update Mitra
                DB::table('mitras')->where('id', $mitra->id)->update([
                    'district_id' => $districtId,
                    'village_id' => $villageId
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn(['district_id', 'village_id']);
        });

        Schema::dropIfExists('villages');
        Schema::dropIfExists('districts');
    }
};
