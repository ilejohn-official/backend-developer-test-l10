<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\CommentWrittenListener;
use App\Listeners\LessonWatchedListener;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
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
    public function it_returns_correct_json_values()
    {
        Event::fake();
        
        $comment = Comment::factory()->create();

        (new CommentWrittenListener)->handle(
            new CommentWritten($comment)
        );

        $lesson = Lesson::first();
        $user = $comment->user;

        $user->lessons()->attach($lesson->id, ['watched' => true]);

        (new LessonWatchedListener)->handle(
            new LessonWatched($lesson, $user)
        );

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Comment Written', 'First Lesson Watched'],
            'next_available_achievements' => ['3 Comments Written', '5 Lessons Watched'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2
        ]);
    }
}
