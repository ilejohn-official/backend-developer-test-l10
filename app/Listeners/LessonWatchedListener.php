<?php

namespace App\Listeners;

use App\Enums\AchievementType;
use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $user = $event->user;

        $lessonsWatchedCount = $user->watched()->count();

        $achievement = Achievement::where('type', AchievementType::LessonsWatched)->firstWhere('unlock_count', $lessonsWatchedCount);

        if(empty($achievement)) {
            return;
        }

        $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

        AchievementUnlocked::dispatch($achievement->name, $user);

        $badge = Badge::firstWhere('unlock_count', $user->achievements()->count());

        if (empty($badge)){
            return;
        }

        BadgeUnlocked::dispatch($badge->name, $user);
    }
}
