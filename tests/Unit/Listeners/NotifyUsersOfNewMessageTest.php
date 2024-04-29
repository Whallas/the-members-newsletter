<?php

namespace Tests\Unit\Listeners;

use App\Events\MessagePosted;
use App\Listeners\NotifyUsersOfNewMessage;
use App\Models\Subscription;
use App\Models\TopicMessage;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\NewTopicMessageNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyUsersOfNewMessageTest extends TestCase
{
    public function testNotifyUsersOfNewMessage()
    {
        $user = User::factory()->make();
        $user->id = 1;

        $topic = Topic::factory()->make(['creator_id' => 1]);
        $topic->id = 1;

        $subscription = new Subscription(['user_id' => 1, 'topic_id' => 1]);
        $subscription->id = 1;


        $message = TopicMessage::factory()->make(['sender_id' => 1, 'topic_id' => 1]);
        $message->id = 1;

        $message->setRelation('topic', $topic);
        $topic->setRelation('subscriptions', Collection::wrap($subscription));
        $topic->setRelation('subscribers', Collection::wrap($user));

        Notification::fake();

        $event = new MessagePosted($message);
        $listener = new NotifyUsersOfNewMessage();
        $listener->handle($event);

        Notification::assertSentTo(
            Collection::wrap($user),
            NewTopicMessageNotification::class,
            function ($notification) use ($message) {
                return $notification->message->id === $message->id;
            }
        );
    }
}
