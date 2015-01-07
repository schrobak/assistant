<?php

namespace Revolve\Assistant\Task;

use League\Event\EmitterInterface;
use Revolve\Assistant\Messenger\MessengerInterface;

interface TaskInterface extends EmitterInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return array
     */
    public function getVariables();

    /**
     * @param MessengerInterface $messenger
     * @param array              $parameters
     *
     * @return $this
     */
    public function write(MessengerInterface $messenger, array $parameters = []);
}
