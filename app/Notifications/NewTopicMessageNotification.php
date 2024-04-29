<?php

namespace App\Notifications;

use App\Models\TopicMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Str;

class NewTopicMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TopicMessage $message)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Message Posted')
            ->greeting('Hello!')
            ->line('A new message has been posted to the topic.')
            ->line(new HtmlString('<code>' . Str::limit($this->message->content, 30) . '</code>'))
            ->line('Thank you for using our application!');
    }
}
