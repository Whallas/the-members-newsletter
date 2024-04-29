<?php

namespace Tests\Feature\Controllers;

use App\Actions\Topic\CreateNewTopic;
use App\Actions\Topic\GetTopics;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TopicControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testUnauthenticatedUser()
    {
        $this->getJson(route('topics.index'))
            ->assertUnauthorized();

        $this->postJson(route('topics.store'))
            ->assertUnauthorized();
    }

    public function testOnlyAdminCanCreateTopic()
    {
        Sanctum::actingAs(User::factory()->asReader()->create());

        $this->postJson(route('topics.store'), [
            'title' => 'Test Topic',
        ])
            ->assertForbidden();
    }

    public function testIndex()
    {
        Sanctum::actingAs(User::factory()->asReader()->create());

        $this->instance(
            GetTopics::class,
            Mockery::mock(GetTopics::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn(Topic::factory()->count(5)->make())
            ))
        );

        $response = $this->getJson(route('topics.index'));

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
    }

    public function testStoreValidData()
    {
        Sanctum::actingAs(User::first());

        $payload = ['title' => 'Test Topic'];

        $this->instance(
            CreateNewTopic::class,
            Mockery::mock(CreateNewTopic::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn($this->makeTopic($payload))
            ))
        );

        $response = $this->postJson(route('topics.store'), $payload);

        $response->assertCreated();
        $response->assertJsonFragment($payload);
    }

    public function testStoreInvalidData()
    {
        Sanctum::actingAs(User::first());

        $payload = ['title' => ''];

        $response = $this->postJson(route('topics.store'), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    private function makeTopic(array $attributes): Topic
    {
        $topic = new Topic($attributes);
        $topic->id = $this->faker->randomNumber();
        $topic->updateTimestamps();
        $topic->wasRecentlyCreated = true;

        return $topic;
    }
}
