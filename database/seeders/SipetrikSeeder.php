<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Link;
use App\Models\Team;

class SipetrikSeeder extends Seeder
{
    public function run()
    {
        // 1. Create or Find Category
        $category = Category::firstOrCreate(
            ['slug' => 'pengelolaan-mitra'],
            ['name' => 'Pengelolaan Mitra', 'order' => 1] // Top order
        );

        // 2. Find Team
        $team = Team::where('name', 'Tim IT')->first();
        if (!$team) {
            $team = Team::first();
        }

        // 3. Create or Update Link
        $link = Link::updateOrCreate(
            ['title' => 'SIPETRIK (Sistem Pengelolaan Mitra Statistik)'],
            [
                'team_id' => $team->id,
                'category_id' => $category->id,
                'url' => '/sipetrik', // Back to Relative URL
                'description' => 'Manajemen database mitra, kontrak kerja, dan penilaian kinerja.',
                'color' => '#f97316',
                'icon' => 'heroicon-o-users',
                'order' => 1,
                'target' => '_self'
            ]
        );
    }
}
