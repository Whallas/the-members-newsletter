<?php

namespace Tests\Unit\Notifications;

use App\Models\TopicMessage;
use App\Notifications\NewTopicMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class NewTopicMessageNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function testNewTopicMessageNotification()
    {
        $topicMessage = new TopicMessage([
            'content' => 'This is a test message',
            'topic_id' => 1,
            'sender_id' => 1,
        ]);

        $notification = new NewTopicMessageNotification($topicMessage);

        // Test the via method
        $this->assertEquals(['mail'], $notification->via($topicMessage));

        // Test the toMail method
        $mailMessage = $notification->toMail($topicMessage);
        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertEquals('New Message Posted', $mailMessage->subject);
        $this->assertContains('A new message has been posted to the topic.', $mailMessage->introLines);
        $this->assertContains('Thank you for using our application!', $mailMessage->introLines);
    }
}
