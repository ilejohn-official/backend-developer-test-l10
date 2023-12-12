<?php

namespace App\Services;

use App\Enums\AchievementType;
use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;

class AchievementService
{
    /**
     * 
     * handle achievement unlock
     */
    public function unlockAchievement(User $user, AchievementType $achievementType): void
    {
        $count = match ($achievementType) {
            AchievementType::CommentsWritten => $user->comments()->count(),
            AchievementType::LessonsWatched => $user->watched()->count(),
            default => 0
        };

        $achievement = Achievement::ofType($achievementType)->firstWhere('unlock_count', $count);

        if(empty($achievement)) {
            return;
        }

        $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);

        AchievementUnlocked::dispatch($achievement->name, $user);
    }
}
