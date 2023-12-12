<?php

namespace Database\Seeders;

use App\Enums\AchievementType;
use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = $this->data();

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }

    private function data(): array
    {
        $lessonsWatchedAchievements = [
            [
                'name' => 'First Lesson Watched',
                'order_position' => 1,
                'unlock_count' => 1
            ],
            [
                'name' => '5 Lessons Watched',
                'order_position' => 2,
                'unlock_count' => 5
            ],
            [
                'name' => '10 Lessons Watched',
                'order_position' => 3,
                'unlock_count' => 10
            ],
            [
                'name' => '25 Lessons Watched',
                'order_position' => 4,
                'unlock_count' => 25
            ],
            [
                'name' => '50 Lessons Watched',
                'order_position' => 5,
                'unlock_count' => 50
            ]
        ];

        foreach ($lessonsWatchedAchievements as &$value) {
            $value['type'] = AchievementType::LessonsWatched;
        }
        unset($value);

        $commentsWrittenAchievements = [
            [
                'name' => 'First Comment Written',
                'order_position' => 1,
                'unlock_count' => 1
            ],
            [
                'name' => '3 Comments Written',
                'order_position' => 2,
                'unlock_count' => 3
            ],
            [
                'name' => '5 Comments Written',
                'order_position' => 3,
                'unlock_count' => 5
            ],
            [
                'name' => '10 Comments Written',
                'order_position' => 4,
                'unlock_count' => 10
            ],
            [
                'name' => '20 Comments Written',
                'order_position' => 5,
                'unlock_count' => 20
            ]
        ];

        foreach ($commentsWrittenAchievements as &$value) {
            $value['type'] = AchievementType::CommentsWritten;
        }
        unset($value);

        return array_merge($lessonsWatchedAchievements, $commentsWrittenAchievements);
    }
}
