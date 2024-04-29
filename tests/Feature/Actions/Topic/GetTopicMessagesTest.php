<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\GetTopicMessages;
use App\Models\TopicMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTopicMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTopicMessages()
    {
        $topicMessages = TopicMessage::factory()->count(3)->create();

        $getTopicMessages = new GetTopicMessages();

        $result = $getTopicMessages->execute([
            'search' => $topicMessages[0]->content,
            'per_page' => 2,
            'page' => 1,
        ]);

        $this->assertEquals(1, $result->total());
        $this->assertEquals($topicMessages[0]->content, $result->first()->content);
    }

    public function testGetTopicMessagesWithoutSearch()
    {
        TopicMessage::factory()->count(3)->create();

        $getTopicMessages = new GetTopicMessages();

        $result = $getTopicMessages->execute([
            'per_page' => 2,
            'page' => 1,
        ]);

        $this->assertEquals(3, $result->total());
        $this->assertCount(2, $result);
    }
}
