<?php

namespace App\Actions\Topic;

use App\Actions\Bases\ModelActionBase;

/**
 * @method \Illuminate\Database\Eloquent\Collection execute(\App\Models\Topic $topic, array $data = [])
 * @property \App\Models\Topic $actionRecord
 */
class GetTopicSubscriptions
{
    use ModelActionBase;

    protected function setParameters(array $data = []): void
    {
        $this->data = [];
    }

    protected function main()
    {
        return $this->actionRecord->subscriptions()
            ->with('user:id,email')
            ->get();
    }
}
