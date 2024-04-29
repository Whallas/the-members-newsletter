<?php

namespace App\Actions\Topic;

use App\Actions\Bases\ModelActionBase;
use App\Models\Topic;
use App\Models\TopicMessage;
use App\Models\User;

/**
 * @method \App\Models\TopicMessage execute(\App\Models\Topic $topic, \App\Models\User $user, array $data = [])
 * @property \App\Models\Topic $actionRecord
 */
class CommentTopic
{
    use ModelActionBase {
        execute as traitExecute;
    }

    private ?User $user;

    protected function setParameters(array $data = []): void
    {
        $this->data = [
            'comment' => $data['comment'] ?? '',
        ];
    }

    public function execute(Topic $actionRecord, User $user, array $data = []): mixed
    {
        $this->user = $user;
        return $this->traitExecute($actionRecord, $data);
    }

    protected function main()
    {
        $message = new TopicMessage([
            'content' => $this->data['comment'],
        ]);
        $message->topic()->associate($this->actionRecord);
        $message->sender()->associate($this->user);
        $message->save();

        return $message;
    }
}
