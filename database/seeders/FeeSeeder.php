<?php

namespace Database\Seeders;

use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fees = [
            [
                'name'       => 'Summer Sale',
                'date_start' => '2026-01-01',
                'date_end'   => '2027-08-31',
            ],
            [
                'name'       => 'Back to School',
                'date_start' => '2025-09-01',
                'date_end'   => '2027-09-30',
            ],
            [
                'name'       => 'Black Friday',
                'date_start' => '2025-11-27',
                'date_end'   => '2029-11-30',
            ],
        ];

        foreach ($fees as $fee) {
            Fee::create($fee);
        }
    }
}
