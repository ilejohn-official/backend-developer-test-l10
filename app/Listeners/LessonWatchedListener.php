<?php

namespace App\Listeners;

use App\Enums\AchievementType;
use App\Events\LessonWatched;
use App\Services\AchievementService;
use App\Services\BadgeService;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     */
    public function __construct
    (
        public AchievementService $achievementService,
        public BadgeService $badgeService
    )
    { }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $user = $event->user;

        $this->achievementService->unlockAchievement($user, AchievementType::LessonsWatched);

        $this->badgeService->handleEarnBadge($user);
    }
}
