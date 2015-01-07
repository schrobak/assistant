<?php

namespace Revolve\Assistant\Connection;

interface ConnectionInterface
{
    /**
     * @return $this
     */
    public function connect();

    /**
     * @return $this
     */
    public function disconnect();

    /**
     * @return bool
     */
    public function isConnected();
}
