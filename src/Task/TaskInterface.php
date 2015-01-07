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
     * @param string $id
     *
     * @return $this
     */
    public function setId($id);

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
     *
     * @return $this
     */
    public function writeTo(MessengerInterface $messenger);

    /**
     * @return mixed
     */
    public function __invoke();
}
