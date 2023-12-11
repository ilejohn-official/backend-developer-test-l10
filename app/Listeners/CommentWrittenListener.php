<?php

namespace App\Listeners;

use App\Enums\AchievementType;
use App\Events\CommentWritten;
use App\Services\AchievementService;
use App\Services\BadgeService;

class CommentWrittenListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public BadgeService $badgeService,
        public AchievementService $achievementService
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        $this->achievementService->unlockAchievement($user, AchievementType::CommentsWritten);

        $this->badgeService->handleBadge($user);
    }
}
