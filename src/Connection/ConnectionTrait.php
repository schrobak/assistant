<?php

namespace Revolve\Assistant\Connection;

use Exception;
use ReflectionClass;

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
     * @throws Exception
     */
    protected function ensureConnected()
    {
        $reflection = new ReflectionClass($this);

        $isConnection = $reflection->implementsInterface(
            "Revolve\\Assistant\\Connection\\ConnectionInterface"
        );

        if ($isConnection and !$this->isConnected()) {
            throw new Exception("You need to connect first!");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
