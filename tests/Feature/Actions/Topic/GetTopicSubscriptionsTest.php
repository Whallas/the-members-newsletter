<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\GetTopicSubscriptions;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTopicSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTopicSubscriptions()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $topic->subscriptions()->create(['user_id' => $user->id]);

        $getTopicSubscriptions = new GetTopicSubscriptions();

        $subscriptions = $getTopicSubscriptions->execute($topic);

        $this->assertCount(1, $subscriptions);
    }
}
