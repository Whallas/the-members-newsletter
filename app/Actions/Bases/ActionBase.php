<?php

namespace App\Actions\Bases;

use Throwable;

abstract class ActionBase
{
    protected array $data = [];

    /**
     * @throws Throwable
     */
    public function execute(array $data = []): mixed
    {
        return $this->init($data);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Throwable
     */
    protected function init(array $data = [])
    {
        // Set parameters
        $this->setParameters($data);
        // Execute main functions
        return $this->main();
    }

    /**
     * Set the parameters used throughout the class from the data passed to it
     * Override this to change how the parameters are set and add validation
     *
     * @param array $data
     */
    abstract protected function setParameters(array $data = []): void;

    /**
     * Main function - add the main logic for the class here
     */
    abstract protected function main();
}
