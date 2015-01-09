<?php

namespace Revolve\Assistant\Provider\Iron;

use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Connection\ConnectionTrait;
use Revolve\Assistant\Exception\ConnectionException;
use Revolve\Assistant\Messenger\QueueMessenger;

class Messenger extends QueueMessenger implements ConnectionInterface
{
    use ConnectionTrait;

    /**
     * @var IronMQ
     */
    protected $iron;

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->iron = null;

            $this->isConnected = false;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if (!$this->isConnected) {
            $config = [
                "token" => $this->config["token"],
                "project_id" => $this->config["project"],
            ];

            $this->iron = $this->make()->object("Revolve\\Assistant\\Provider\\Iron\\IronMQ");
            $this->iron->setConfig($config);

            $this->isConnected = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function read()
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        while ($item = $this->iron->getMessage($namespace)) {
            $this->messages[$item->id] = $item->body;
        }

        return $this->messages;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function write($message)
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        $this->iron->postMessage($namespace, $message, [
            "timeout" => 0,
            "delay" => 0,
            "expires_in" => 24 * 3600,
        ]);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function remove($message)
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        $new = [];

        foreach ($this->messages as $id => $body) {
            if ($body === $message) {
                $this->iron->deleteMessage($namespace, $id);
            } else {
                $new[$id] = $body;
            }
        }

        $this->messages = $new;

        return $this;
    }
}
