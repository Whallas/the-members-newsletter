<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\SubscribeUser;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeUserTest extends TestCase
{
    use RefreshDatabase;

    public function testSubscribeUser()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();

        $subscribeUser = new SubscribeUser();

        $subscription = $subscribeUser->execute($user, $topic);

        $this->assertEquals($topic->id, $subscription->topic_id);
        $this->assertEquals($user->id, $subscription->user_id);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'topic_id' => $topic->id
        ]);
    }
}
