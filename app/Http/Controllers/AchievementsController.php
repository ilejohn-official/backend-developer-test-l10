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
        $achievementTypes = AchievementType::cases();
        
        foreach($achievementTypes as $achievementType){

            $latest = $achievements->where('type', $achievementType)->sortByDesc('order_position')->first();

            if (empty($latest)){
                $nextAvailableAchievements[] = Achievement::ofType($achievementType)->where('order_position', 1)->value('name');
                continue;
            }

            $next = Achievement::ofType($achievementType)->where('order_position', $latest->order_position + 1)->value('name');

            if (filled($next)){
                $nextAvailableAchievements[] = $next;
            } 
        }

        $currentBadge = $user->currentBadge();
        $nextBadge = Badge::firstWhere('order_position', $currentBadge->order_position + 1);
        $remainingToUnlockNextBadge = filled($nextBadge) ? $nextBadge->unlock_count - $achievements->count() : 0;

        return response()->json([
            'unlocked_achievements' => $achievements->pluck('name'),
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge?->name,
            'next_badge' => $nextBadge?->name,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
