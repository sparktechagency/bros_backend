<?php

namespace Database\Seeders;

use App\Models\ManageDate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManageDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ManageDate::factory()->count(20)->create();
    }
}
