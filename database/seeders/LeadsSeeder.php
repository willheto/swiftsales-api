<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;

class LeadsSeeder extends Seeder
{
    /**
     * Run the leads seeder.
     *
     * @return void
     */
    public function run()
    {
        Lead::factory()->count(100)->create();
    }
}
