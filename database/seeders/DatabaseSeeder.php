<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Category;
use App\Models\Link;
use App\Models\Announcement;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create regular users
        $users = [
            ['name' => 'John Doe', 'email' => 'john@company.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@company.com'],
            ['name' => 'Bob Wilson', 'email' => 'bob@company.com'],
            ['name' => 'Alice Brown', 'email' => 'alice@company.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        // Create teams
        $teams = [
            [
                'name' => 'Tim IT',
                'description' => 'Tim Teknologi Informasi',
                'color' => '#3b82f6',
                'icon' => 'heroicon-o-computer-desktop',
            ],
            [
                'name' => 'Tim HR',
                'description' => 'Tim Human Resources',
                'color' => '#10b981',
                'icon' => 'heroicon-o-users',
            ],
            [
                'name' => 'Tim Finance',
                'description' => 'Tim Keuangan dan Akuntansi',
                'color' => '#f59e0b',
                'icon' => 'heroicon-o-banknotes',
            ],
            [
                'name' => 'Tim Marketing',
                'description' => 'Tim Pemasaran dan Promosi',
                'color' => '#ec4899',
                'icon' => 'heroicon-o-megaphone',
            ],
        ];

        foreach ($teams as $teamData) {
            $team = Team::create($teamData);

            // Assign users to teams
            $userIds = User::where('role', 'user')->inRandomOrder()->take(rand(2, 4))->pluck('id');
            $team->users()->attach($userIds);
            $team->users()->attach($admin->id); // Admin is in all teams
        }

        // Create categories
        $categories = [
            ['name' => 'Development Tools', 'slug' => 'development-tools', 'order' => 1],
            ['name' => 'Communication', 'slug' => 'communication', 'order' => 2],
            ['name' => 'Project Management', 'slug' => 'project-management', 'order' => 3],
            ['name' => 'Documentation', 'slug' => 'documentation', 'order' => 4],
            ['name' => 'Analytics', 'slug' => 'analytics', 'order' => 5],
            ['name' => 'Finance Tools', 'slug' => 'finance-tools', 'order' => 6],
            ['name' => 'HR Systems', 'slug' => 'hr-systems', 'order' => 7],
            ['name' => 'Marketing Tools', 'slug' => 'marketing-tools', 'order' => 8],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create links for IT Team
        $itTeam = Team::where('name', 'Tim IT')->first();
        $devCategory = Category::where('slug', 'development-tools')->first();
        $projectCategory = Category::where('slug', 'project-management')->first();

        $itLinks = [
            [
                'category_id' => $devCategory->id,
                'title' => 'GitHub',
                'url' => 'https://github.com',
                'description' => 'Version control dan kolaborasi kode',
                'color' => '#24292e',
                'order' => 1,
            ],
            [
                'category_id' => $devCategory->id,
                'title' => 'GitLab Internal',
                'url' => 'http://gitlab.internal.company',
                'description' => 'GitLab server internal perusahaan',
                'color' => '#fc6d26',
                'order' => 2,
            ],
            [
                'category_id' => $projectCategory->id,
                'title' => 'Jira',
                'url' => 'https://company.atlassian.net/jira',
                'description' => 'Issue tracking dan project management',
                'color' => '#0052cc',
                'order' => 3,
            ],
        ];

        foreach ($itLinks as $linkData) {
            $itTeam->links()->create($linkData);
        }

        // Create links for HR Team
        $hrTeam = Team::where('name', 'Tim HR')->first();
        $hrCategory = Category::where('slug', 'hr-systems')->first();

        $hrLinks = [
            [
                'category_id' => $hrCategory->id,
                'title' => 'HRIS System',
                'url' => 'http://hris.internal.company',
                'description' => 'Human Resource Information System',
                'color' => '#059669',
                'order' => 1,
            ],
            [
                'category_id' => $hrCategory->id,
                'title' => 'LinkedIn Learning',
                'url' => 'https://www.linkedin.com/learning',
                'description' => 'Platform pembelajaran karyawan',
                'color' => '#0a66c2',
                'order' => 2,
            ],
        ];

        foreach ($hrLinks as $linkData) {
            $hrTeam->links()->create($linkData);
        }

        // Create announcements
        Announcement::create([
            'team_id' => null, // Global announcement
            'title' => 'Pemeliharaan Server Scheduled',
            'content' => 'Server maintenance akan dilakukan pada hari Sabtu, 20 Januari 2024 pukul 00:00 - 06:00 WIB.',
            'priority' => 'high',
            'is_active' => true,
        ]);

        Announcement::create([
            'team_id' => $itTeam->id,
            'title' => 'Update GitLab ke versi 16.7',
            'content' => 'GitLab internal akan diupdate ke versi terbaru.',
            'priority' => 'normal',
            'is_active' => true,
        ]);

        echo "Seeding completed successfully!\n";
    }
}
