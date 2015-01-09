<?php

namespace Revolve\Assistant\Connection;

use ReflectionClass;
use Revolve\Assistant\Exception\ConnectionException;

trait ConnectionTrait
{
    /**
     * @var bool
     */
    protected $isConnected = false;

    public function __sleep()
    {
        return ["config", "isConnected"];
    }

    public function __wakeup()
    {
        if ($this->isConnected) {
            $this->isConnected = false;

            $this->connect();
        }
    }

    /**
     * @return $this
     */
    abstract public function connect();

    /**
     * @throws ConnectionException
     */
    protected function ensureConnected()
    {
        $reflection = new ReflectionClass($this);

        if ($this->isConnection($reflection) and !$this->isConnected()) {
            throw new ConnectionException("You need to connect first!");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isConnection(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Connection\\ConnectionInterface"
        );
    }
}
