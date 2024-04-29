<?php

namespace App\Actions\Bases;

/**
 * Trait ModelActionBase
 */
trait ModelActionBase
{
    protected $actionRecord;

    protected array $data = [];

    /**
     * @param \Eloquent|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder $actionRecord
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function execute($actionRecord, array $data = []): mixed
    {
        // Set global model
        $this->actionRecord = $actionRecord;
        return $this->init($data);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    protected function init(array $data = [])
    {
        // Set parameters
        if (!empty($data) || isset($this->setParameters)) {
            $this->setParameters($data);
        }

        // Execute main functions
        return $this->main();
    }

    /**
     * Set the parameters used throughout the class from the data passed to it
     * Override this to change how the parameters are set and add validation
     *
     * @param array $data
     */
    abstract protected function setParameters(array $data): void;

    /**
     * Main function - add the main logic for the class here
     */
    abstract protected function main();
}
