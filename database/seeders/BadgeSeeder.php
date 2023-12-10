<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Beginner',
                'order_position' => 1,
                'unlock_count' => 0
            ],
            [
                'name' => 'Intermediate',
                'order_position' => 2,
                'unlock_count' => 4
            ],
            [
                'name' => 'Advanced',
                'order_position' => 3,
                'unlock_count' => 8
            ],
            [
                'name' => 'Master',
                'order_position' => 4,
                'unlock_count' => 10
            ]
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
