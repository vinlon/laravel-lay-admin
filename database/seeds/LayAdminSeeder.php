<?php

use Illuminate\Database\Seeder;

class LayAdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(InitMenuSeeder::class);
        $this->call(InitRoleSeeder::class);
    }
}
