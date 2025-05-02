<?php
namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $services = [
            [
                'car_type'   => 'Compact',
                'icon'       => 'compact.png',
                'interior'   => 60.00,
                'exterior'   => 70.00,
                'both'       => 120.00,
                'time'       => json_encode(["09:00 AM", "10:30 AM", "12:00 PM", "01:00 PM"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'car_type'   => 'SUV',
                'icon'       => 'suv.png',
                'interior'   => 75.00,
                'exterior'   => 85.00,
                'both'       => 140.00,
                'time'       => json_encode(["09:30 AM", "11:00 AM", "12:30 PM", "02:00 PM"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'car_type'   => 'Sports Car',
                'icon'       => 'sports_car.png',
                'interior'   => 90.00,
                'exterior'   => 100.00,
                'both'       => 160.00,
                'time'       => json_encode(["10:00 AM", "11:30 AM", "01:00 PM", "02:30 PM"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'car_type'   => 'Truck',
                'icon'       => 'truck.png',
                'interior'   => 80.00,
                'exterior'   => 90.00,
                'both'       => 150.00,
                'time'       => json_encode(["08:30 AM", "10:00 AM", "11:30 AM", "01:00 PM"]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('services')->insert($services);
    }
}
