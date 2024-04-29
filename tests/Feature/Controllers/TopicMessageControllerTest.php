<?php

namespace Tests\Feature\Controllers;

use App\Actions\Topic\CommentTopic;
use App\Actions\Topic\GetTopicMessages;
use App\Models\TopicMessage;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TopicMessageControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testUnauthenticatedUser()
    {
        $topic = Topic::factory()->create();

        $this->getJson(route('topics.messages.index', $topic))
            ->assertUnauthorized();

        $this->postJson(route('topics.messages.store', $topic))
            ->assertUnauthorized();
    }

    public function testIndex()
    {
        $user = User::factory()->asReader()->create();
        Sanctum::actingAs($user);

        $topic = Topic::factory()->create();

        $this->instance(
            GetTopicMessages::class,
            Mockery::mock(GetTopicMessages::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn(
                        TopicMessage::factory()->count(5)->for($topic)->for($user, 'sender')->make()
                    )
            ))
        );

        $response = $this->getJson(route('topics.messages.index', $topic));

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
    }

    public function testStore()
    {
        $user = User::factory()->asReader()->create();
        Sanctum::actingAs($user);

        $topic = Topic::factory()->create();

        $this->instance(
            CommentTopic::class,
            Mockery::mock(CommentTopic::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn($this->makeTopicMessage($topic, $user))
            ))
        );

        $response = $this->postJson(route('topics.messages.store', $topic), [
            'comment' => $this->faker->sentence
        ]);

        $response->assertCreated();
    }

    private function makeTopicMessage(Topic $topic, User $user): TopicMessage
    {
        $message = new TopicMessage([
            'topic_id' => $topic->id,
            'sender_id' => $user->id,
            'content' => $this->faker->sentence(),
        ]);
        $message->id = $this->faker->randomNumber();
        $message->updateTimestamps();
        $message->wasRecentlyCreated = true;

        return $message;
    }
}
