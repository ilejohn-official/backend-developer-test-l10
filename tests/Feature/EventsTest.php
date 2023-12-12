<?php

namespace Tests\Feature;

use App\Enums\AchievementType;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\CommentWrittenListener;
use App\Listeners\LessonWatchedListener;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use App\Services\AchievementService;
use App\Services\BadgeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function that_comment_written_listener_listens_to_comment_written_event(): void
    { 
        Event::fake();

        Event::assertListening(
            CommentWritten::class,
            CommentWrittenListener::class
        );
    }

    /** @test */
    public function that_lesson_watched_listener_listens_to_lesson_watched_event(): void
    { 
        Event::fake();

        Event::assertListening(
            LessonWatched::class,
            LessonWatchedListener::class
        );
    }

    /** @test */
    public function that_achievement_unlocked_event_is_fired_after_an_achievement_with_correct_payload(): void
    {
        Event::fake();

        $user = User::factory()->create();
        Comment::factory()->count(10)->create(['user_id' => $user->id]);

        app(AchievementService::class)->unlockAchievement($user, AchievementType::CommentsWritten);

        Event::assertDispatched(function (AchievementUnlocked $event) use ($user) {
            return $event->achievement_name === '10 Comments Written' && $event->user->id === $user->id;
        });
    }

    /** @test */
    public function that_achievement_unlocked_event_is_not_fired_after_an_achievement_is_not_unlocked(): void
    {
        Event::fake();

        $user = User::factory()->create();
        Comment::factory()->count(9)->create(['user_id' => $user->id]);

        app(AchievementService::class)->unlockAchievement($user, AchievementType::CommentsWritten);

        Event::assertNotDispatched(AchievementUnlocked::class);
    }

    /** @test */
    public function that_badge_unlocked_event_is_fired_after_a_badge_earn_with_correct_payload(): void
    {
        Event::fake();
        
        $user = User::factory()->create();
        $achievementIds = Achievement::limit(10)->pluck('id');
        $user->achievements()->syncWithPivotValues($achievementIds, ['unlocked_at' => now()]);

        app(BadgeService::class)->handleEarnBadge($user);

        Event::assertDispatched(function (BadgeUnlocked $event) use ($user) {
            return $event->badge_name === 'Master' && $event->user->id === $user->id;
        });
    }

    /** @test */
    public function that_badge_unlocked_event_is_not_fired_after_a_badge_is_not_earned(): void
    {
        Event::fake();
        
        $user = User::factory()->create();
        $achievementIds = Achievement::limit(7)->pluck('id');
        $user->achievements()->syncWithPivotValues($achievementIds, ['unlocked_at' => now()]);

        app(BadgeService::class)->handleEarnBadge($user);

        Event::assertNotDispatched(BadgeUnlocked::class);
    }
}
