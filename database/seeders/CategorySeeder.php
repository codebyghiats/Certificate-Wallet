<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories table with default values.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Identitas', 'icon' => 'id-card'],
            ['name' => 'Sertifikasi IT', 'icon' => 'monitor-check'],
            ['name' => 'Akademik', 'icon' => 'graduation-cap'],
            ['name' => 'Penghargaan', 'icon' => 'trophy'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
