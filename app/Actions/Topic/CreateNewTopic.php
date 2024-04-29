<?php

namespace App\Actions\Topic;

use App\Actions\Bases\ModelActionBase;

/**
 * @method \App\Models\Topic execute(App\Models\User $user, array $data = [])
 * @property \App\Models\User $actionRecord
 */
class CreateNewTopic
{
    use ModelActionBase;

    protected function setParameters(array $data = []): void
    {
        $this->data = [
            'title' => $data['title'] ?? '',
        ];
    }

    protected function main()
    {
        return $this->actionRecord->myTopics()
            ->create($this->data);
    }
}
