<?php

namespace Revolve\Assistant\Client;

use Revolve\Assistant\Task\TaskInterface;
use Revolve\Assistant\Messenger\MessengerInterface;

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
    public function isProcessing(TaskInterface $task);

    /**
     * @param TaskInterface $task
     *
     * @return bool
     */
    public function hasProcessed(TaskInterface $task);

    /**
     * @param MessengerInterface $messenger
     *
     * @return $this
     */
    public function read(MessengerInterface $messenger);
}
