<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('skills')->insert([
            ['skill_name' => 'First Aid', 'description' => 'Emergency response skills', 'category' => 'Health'],
            ['skill_name' => 'Teaching', 'description' => 'Educational volunteer work', 'category' => 'Education'],
            ['skill_name' => 'Cooking', 'description' => 'Meal preparation and assistance', 'category' => 'Community Service'],
        ]);
    }
}
