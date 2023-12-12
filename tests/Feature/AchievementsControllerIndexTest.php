<?php

namespace Tests\Feature;

use App\Enums\AchievementType;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use App\Services\AchievementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AchievementsControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_correct_json_response_structure(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonStructure([
            'unlocked_achievements',
            'next_available_achievements',
            'current_badge',
            'next_badge',
            'remaining_to_unlock_next_badge',
        ]);
    }

    /** @test */
    public function it_returns_correct_json_values(): void
    {
        Event::fake();
        $service = app(AchievementService::class);
        
        $comment = Comment::factory()->create();
        $user = $comment->user;
        $service->unlockAchievement($user, AchievementType::CommentsWritten);

        $lesson = Lesson::first();
        $user->lessons()->attach($lesson->id, ['watched' => true]);
        $service->unlockAchievement($user, AchievementType::LessonsWatched);

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJsonFragment([
            'unlocked_achievements' => ['First Comment Written', 'First Lesson Watched'],
            'next_available_achievements' => ['3 Comments Written', '5 Lessons Watched'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2
        ]);
    }
}
