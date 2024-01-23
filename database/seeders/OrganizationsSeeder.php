<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the organizations seeder.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory()->count(5)->create();
    }
}
