<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;

class CommentWrittenListener
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
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;

        $commentsCount = $user->comments()->count();

        $achievement = Achievement::where('type', 'comments_written')->firstWhere('unlock_count', $commentsCount);

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
