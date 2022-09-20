<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Project::factory()->create([
            'name' => 'Soultatva',
        ]);
        \App\Models\Project::factory()->create([
            'name' => 'Ecommerce',
        ]);
    }
}
