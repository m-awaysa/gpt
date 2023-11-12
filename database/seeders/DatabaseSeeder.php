<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $recipes = [
            'مشروب 1' => 'مشروب ساخن كبير من الشاي مع سكر وإضافة النعناع.',
            'مشروب 2' =>"مشروب ساخن من الشاي بدون سكر مع إضافة الحليب، متوفر بحجم كبير وصغير.",
            'مشروب 3' =>"مشروب ساخن من القهوة بدون سكر مع إضافة الحليب، متوفر بحجم كبير وصغير.",
            'مشروب 4' =>"مشروب بارد من عصير التفاح مع سكر، متوفر بحجم كبير وصغير وبدون إضافات.",
        ];

        foreach ($recipes as $name => $recipe) {
            Recipe::create([
                'name' =>  $name,
                'description' => $recipe
            ]);
        }
    }
}
