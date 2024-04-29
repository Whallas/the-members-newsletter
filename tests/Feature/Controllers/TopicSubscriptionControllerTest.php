<?php

namespace Tests\Feature\Controllers;

use App\Actions\Topic\GetTopicSubscriptions;
use App\Actions\Topic\SubscribeUser;
use App\Models\Subscription;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TopicSubscriptionControllerTest extends TestCase
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

        $this->getJson(route('topics.subscriptions.index', $topic))
            ->assertUnauthorized();

        $this->postJson(route('topics.subscriptions.store', $topic))
            ->assertUnauthorized();

        $subscription = Subscription::factory()->create();

        $this->deleteJson(route('topics.subscriptions.destroy', $subscription))
            ->assertUnauthorized();
    }

    public function testIndex()
    {
        $user = User::factory()->asReader()->create();
        Sanctum::actingAs($user);

        $topic = Topic::factory()->create();

        $this->instance(
            GetTopicSubscriptions::class,
            Mockery::mock(GetTopicSubscriptions::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn(
                        Subscription::factory()->count(5)->for($topic)->for($user)->make()
                    )
            ))
        );

        $response = $this->getJson(route('topics.subscriptions.index', $topic));

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
    }

    public function testStore()
    {
        $user = User::factory()->asReader()->create();
        Sanctum::actingAs($user);

        $topic = Topic::factory()->create();

        $this->instance(
            SubscribeUser::class,
            Mockery::mock(SubscribeUser::class, fn (MockInterface $mock) => (
                $mock->shouldReceive('execute')->once()
                    ->andReturn($this->makeSubscription($topic, $user))
            ))
        );

        $response = $this->postJson(route('topics.subscriptions.store', $topic));

        $response->assertCreated();
    }

    public function testDestroyAsAdmin()
    {
        $user = User::factory()->asAdmin()->create();
        Sanctum::actingAs($user);

        $subscription = Subscription::factory()->create();

        $response = $this->deleteJson(route('topics.subscriptions.destroy', $subscription));

        $response->assertNoContent();
    }

    public function testDestroyAsReader()
    {
        $user = User::factory()->asReader()->create();
        Sanctum::actingAs($user);

        $subscription = Subscription::factory()->for($user)->create();

        $response = $this->deleteJson(route('topics.subscriptions.destroy', $subscription));

        $response->assertNoContent();
    }

    public function testReaderCannotUnsubscribeAnotherReader()
    {
        $user1 = User::factory()->asReader()->create();
        $user2 = User::factory()->asReader()->create();
        Sanctum::actingAs($user1);
        $subscription = Subscription::factory()->for($user2)->create();
        $response = $this->deleteJson(route('topics.subscriptions.destroy', $subscription));
        $response->assertForbidden();
    }

    private function makeSubscription(Topic $topic, User $user): Subscription
    {
        $subscription = new Subscription([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
        ]);
        $subscription->id = $this->faker->randomNumber();
        $subscription->updateTimestamps();
        $subscription->wasRecentlyCreated = true;

        return $subscription;
    }
}
