<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        
        if (Table::count() === 0) {
            Table::factory()->count(20)->create();
        }

        $tables = Table::pluck('id');

        $timeRanges = [
            ['12:00', '16:00'],
            ['19:00', '23:00'],
        ];

        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {

            $day = Carbon::today()->subDays($daysAgo);
            $ordersPerDay = rand(5, 15);

            for ($i = 0; $i < $ordersPerDay; $i++) {

                [$start, $end] = $timeRanges[array_rand($timeRanges)];

                $hour = rand(
                    Carbon::createFromTimeString($start)->hour,
                    Carbon::createFromTimeString($end)->hour - 1
                );

                $minute = rand(0, 59);

                $date = $day->copy()
                    ->setHour($hour)
                    ->setMinute($minute)
                    ->setSecond(0);

                Order::factory()->create([
                    'table_id' => $tables->random(),
                    'date' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
