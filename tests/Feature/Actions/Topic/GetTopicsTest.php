<?php

namespace Tests\Feature\Actions\Topic;

use App\Actions\Topic\GetTopics;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTopicsTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTopics()
    {
        $topics = Topic::factory()->count(3)->create();

        $getTopics = new GetTopics();

        $result = $getTopics->execute([
            'search' => $topics[0]->title,
            'per_page' => 2,
            'page' => 1,
        ]);

        $this->assertEquals(1, $result->total());
        $this->assertEquals($topics[0]->title, $result->first()->title);
    }

    public function testGetTopicsWithoutSearch()
    {
        Topic::factory()->count(3)->create();

        $getTopics = new GetTopics();

        $result = $getTopics->execute([
            'per_page' => 2,
            'page' => 1,
        ]);

        $this->assertEquals(3, $result->total());
        $this->assertCount(2, $result);
    }
}
