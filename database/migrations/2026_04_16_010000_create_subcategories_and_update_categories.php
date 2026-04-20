<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 0. Make access_logs.user_id nullable (for public link access)
        Schema::table('access_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // 1. Update categories table
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('is_active');
            }
        });

        // 2. Create subcategories table
        if (!Schema::hasTable('subcategories')) {
            Schema::create('subcategories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
                $table->string('name');
                $table->string('slug');
                $table->string('icon')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['category_id', 'slug']);
            });
        }

        // 3. Add subcategory_id to links
        Schema::table('links', function (Blueprint $table) {
            if (!Schema::hasColumn('links', 'subcategory_id')) {
                $table->foreignId('subcategory_id')->nullable()->after('category_id')
                    ->constrained()->onDelete('set null');
            }
        });

        // 4. Seed 12 locked categories
        $categories = [
            ['name' => 'Persiapan Survei/Sensus', 'icon' => 'clipboard-document-list', 'order' => 1],
            ['name' => 'Pengumpulan Data Survei/Sensus', 'icon' => 'chart-bar', 'order' => 2],
            ['name' => 'Pengolahan dan Analisis Survei/Sensus', 'icon' => 'beaker', 'order' => 3],
            ['name' => 'Diseminasi Survei/Sensus', 'icon' => 'signal', 'order' => 4],
            ['name' => 'Perencanaan dan Anggaran', 'icon' => 'calendar-days', 'order' => 5],
            ['name' => 'Keuangan', 'icon' => 'banknotes', 'order' => 6],
            ['name' => 'Pengelolaan SDM', 'icon' => 'user-group', 'order' => 7],
            ['name' => 'Layanan Hukum dan Kerjasama', 'icon' => 'scale', 'order' => 8],
            ['name' => 'Layanan Hukum dan Perkantoran', 'icon' => 'building-office', 'order' => 9],
            ['name' => 'Layanan TIK', 'icon' => 'computer-desktop', 'order' => 10],
            ['name' => 'Pengendalian Intern', 'icon' => 'shield-check', 'order' => 11],
            ['name' => 'Pendidikan dan Pelatihan', 'icon' => 'academic-cap', 'order' => 12],
        ];

        // Deactivate all old categories
        DB::table('categories')->update(['is_active' => false]);

        foreach ($categories as $cat) {
            $slug = Str::slug($cat['name']);
            
            $existing = DB::table('categories')->where('slug', $slug)->first();
            
            if ($existing) {
                DB::table('categories')->where('id', $existing->id)->update([
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'order' => $cat['order'],
                    'is_active' => true,
                    'is_locked' => true,
                    'updated_at' => now(),
                ]);
                $categoryId = $existing->id;
            } else {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $cat['name'],
                    'slug' => $slug,
                    'icon' => $cat['icon'],
                    'order' => $cat['order'],
                    'is_active' => true,
                    'is_locked' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create "Umum" subcategory for each new category
            $subExists = DB::table('subcategories')
                ->where('category_id', $categoryId)
                ->where('slug', 'umum')
                ->exists();

            if (!$subExists) {
                DB::table('subcategories')->insert([
                    'category_id' => $categoryId,
                    'team_id' => null,
                    'name' => 'Umum',
                    'slug' => 'umum',
                    'icon' => null,
                    'order' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. Migrate existing links: assign to "Umum" subcategory of their current category
        $links = DB::table('links')->whereNull('subcategory_id')->whereNotNull('category_id')->get();
        foreach ($links as $link) {
            $sub = DB::table('subcategories')
                ->where('category_id', $link->category_id)
                ->where('slug', 'umum')
                ->first();

            if ($sub) {
                DB::table('links')->where('id', $link->id)->update([
                    'subcategory_id' => $sub->id,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            if (Schema::hasColumn('links', 'subcategory_id')) {
                $table->dropForeign(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            }
        });

        Schema::dropIfExists('subcategories');

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'icon')) {
                $table->dropColumn('icon');
            }
            if (Schema::hasColumn('categories', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
        });
    }
};
