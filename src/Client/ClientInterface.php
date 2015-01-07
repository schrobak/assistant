<?php

namespace Revolve\Assistant\Client;

use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\TaskInterface;

interface ClientInterface
{
    /**
     * @param TaskInterface $task
     *
     * @return $this
     */
    public function handle(TaskInterface $task);

    /**
     * @param TaskInterface $task
     *
     * @return bool
     */
    public function hasCompleted(TaskInterface $task);

    /**
     * @param MessengerInterface $messenger
     *
     * @return $this
     */
    public function read(MessengerInterface $messenger);
}
