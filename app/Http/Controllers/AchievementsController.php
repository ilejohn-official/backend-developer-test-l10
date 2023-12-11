<?php

namespace App\Http\Controllers;

use App\Enums\AchievementType;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AchievementsController extends Controller
{
    public function index(User $user): JsonResponse
    { 
        $achievements = $user->achievements;
        $nextAvailableAchievements = [];
        $achievementTypes = AchievementType::values();
        
        foreach($achievementTypes as $achievementType){
            $achievementTypeEnum = AchievementType::tryFrom($achievementType);

            $latest = $achievements->where('type', $achievementTypeEnum)->sortByDesc('order_position')->first();

            if (empty($latest)){
                $nextAvailableAchievements[] = Achievement::where('type', $achievementTypeEnum)->where('order_position', 1)->value('name');
                continue;
            }

            $next = Achievement::where('type', $achievementTypeEnum)->where('order_position', $latest->order_position + 1)->value('name');

            if (filled($next)){
                $nextAvailableAchievements[] = $next;
            } 
        }
        
        $numberOfAchievements = $achievements->count();

        $badgeUnlockCount = match (true) {
            $numberOfAchievements >= 10 => 10,
            $numberOfAchievements >= 8 => 8,
            $numberOfAchievements >= 4 => 4,
            default => 0,
        };

        $currentBadge = Badge::firstWhere('unlock_count', $badgeUnlockCount);
        $nextBadge = Badge::firstWhere('order_position', $currentBadge->order_position + 1);
        $remainingToUnlockNextBadge = filled($nextBadge) ? $nextBadge->unlock_count - $numberOfAchievements : 0;

        return response()->json([
            'unlocked_achievements' => $achievements->pluck('name'),
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge?->name,
            'next_badge' => $nextBadge?->name,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
