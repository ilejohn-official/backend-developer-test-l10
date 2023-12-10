<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([AchievementSeeder::class, BadgeSeeder::class]);

        $lessons = Lesson::factory()
            ->count(20)
            ->create();
    }
}
