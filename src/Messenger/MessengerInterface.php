<?php

namespace Revolve\Assistant\Messenger;

interface MessengerInterface
{
    /**
     * @return array
     */
    public function read();

    /**
     * @param string $message
     *
     * @return $this
     */
    public function write($message);

    /**
     * @param string $message
     *
     * @return $this
     */
    public function remove($message);
}
