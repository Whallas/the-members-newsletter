<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\CommentTopic;
use App\Events\MessagePosted;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentTopicTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentTopic()
    {
        Event::fake();

        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $comment = 'This is a comment';

        $commentTopic = new CommentTopic();

        $message = $commentTopic->execute($topic, $user, ['comment' => $comment]);

        $this->assertEquals($comment, $message->content);
        $this->assertEquals($topic->id, $message->topic_id);
        $this->assertEquals($user->id, $message->sender_id);

        // Check if the items are in the database
        $this->assertDatabaseHas($message->getTable(), [
            'content' => $comment,
            'topic_id' => $topic->id,
            'sender_id' => $user->id
        ]);

        Event::assertDispatched(function (MessagePosted $event) use ($message) {
            return $event->message->id === $message->id;
        });
    }
}
