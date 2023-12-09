<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;

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

        $achievement = Achievement::where('type', 'lessons_watched')->firstWhere('unlock_count', $lessonsWatchedCount);

        if(empty($achievement)) {
            return;
        }

        $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

        AchievementUnlocked::dispatch($achievement->name, $user);
    }
}
