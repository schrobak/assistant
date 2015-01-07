<?php

namespace Revolve\Assistant\Worker;

use Revolve\Assistant\Task\TaskInterface;

abstract class Worker implements WorkerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function handle(TaskInterface $task);

    /**
     * {@inheritdoc}
     */
    abstract public function run();
}