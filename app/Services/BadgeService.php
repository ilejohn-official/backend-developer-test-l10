<?php

namespace App\Services;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    /**
     * 
     * handle badge unlock
     */
    public function handleEarnBadge(User $user): void
    {
        $badge = Badge::firstWhere('unlock_count', $user->achievements()->count());

        if (empty($badge)){
            return;
        }

        BadgeUnlocked::dispatch($badge->name, $user);
    }
}
