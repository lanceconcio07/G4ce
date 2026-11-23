<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            ConsultantSeeder::class,
        ]);
    }
}
