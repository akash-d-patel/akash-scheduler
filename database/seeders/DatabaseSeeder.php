<?php

namespace Database\Seeders;

use Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClientSeeder::class,
            UserSeeder::class,
            ProjectSeeder::class,
            EmailTemplateSeeder::class
        ]);
        Artisan::call("passport:purge");
        Artisan::call("passport:install");
        Artisan::call("telescope:clear");
    }
}
