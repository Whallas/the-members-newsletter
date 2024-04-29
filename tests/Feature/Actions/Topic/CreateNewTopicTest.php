<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\CreateNewTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNewTopicTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateNewTopic()
    {
        $user = User::factory()->create();
        $title = 'New Topic Title';

        $createNewTopic = new CreateNewTopic();

        $topic = $createNewTopic->execute($user, ['title' => $title]);

        $this->assertEquals($title, $topic->title);
        $this->assertEquals($user->id, $topic->creator_id);

        // Check if the items are in the database
        $this->assertDatabaseHas('topics', [
            'title' => $title,
            'creator_id' => $user->id
        ]);
    }
}
