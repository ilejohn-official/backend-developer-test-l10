<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = $this->data();

        foreach ($achievements as $achievement) {
            $achievement = array_merge($achievement, [
                'identifier' => Str::snake($achievement['name'])
            ]);

            Achievement::create($achievement);
        }
    }

    private function data()
    {
        $lessonsWatchedAchievements = [
            [
                'name' => 'First Lesson Watched',
                'order_position' => 1,
                'unlock_count' => 1,
                'type' => 'lessons_watched'
            ],
            [
                'name' => '5 Lessons Watched',
                'order_position' => 2,
                'unlock_count' => 5,
                'type' => 'lessons_watched'
            ],
            [
                'name' => '10 Lessons Watched',
                'order_position' => 3,
                'unlock_count' => 10,
                'type' => 'lessons_watched'
            ],
            [
                'name' => '25 Lessons Watched',
                'order_position' => 4,
                'unlock_count' => 25,
                'type' => 'lessons_watched'
            ],
            [
                'name' => '50 Lessons Watched',
                'order_position' => 5,
                'unlock_count' => 50,
                'type' => 'lessons_watched'
            ]
        ];

        $commentsWrittenAchievements = [
            [
                'name' => 'First Comment Written',
                'order_position' => 1,
                'unlock_count' => 1,
                'type' => 'comments_written'
            ],
            [
                'name' => '3 Comments Written',
                'order_position' => 2,
                'unlock_count' => 3,
                'type' => 'comments_written'
            ],
            [
                'name' => '5 Comments Written',
                'order_position' => 3,
                'unlock_count' => 5,
                'type' => 'comments_written'
            ],
            [
                'name' => '10 Comments Written',
                'order_position' => 4,
                'unlock_count' => 10,
                'type' => 'comments_written'
            ],
            [
                'name' => '20 Comments Written',
                'order_position' => 5,
                'unlock_count' => 20,
                'type' => 'comments_written'
            ]
        ];

        return array_merge($lessonsWatchedAchievements, $commentsWrittenAchievements);
    }
}
