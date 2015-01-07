<?php

namespace Revolve\Assistant\Worker;

use Revolve\Assistant\Task\TaskInterface;

interface WorkerInterface
{
    /**
     * @param TaskInterface $task
     *
     * @return mixed
     */
    public function handle(TaskInterface $task);

    /**
     * @return $this
     */
    public function run();
}
