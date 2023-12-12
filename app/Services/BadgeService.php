<?php

namespace App\Services;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    /**
     * 
     * handle earning badge
     */
    public function handleEarnBadge(User $user): void
    {
        $badge = Badge::firstWhere('unlock_count', $user->achievements()->count());

        if (empty($badge)){
            return;
        }

        $user->badges()->attach($badge->id);

        BadgeUnlocked::dispatch($badge->name, $user);
    }
}
