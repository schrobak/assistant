<?php

namespace Revolve\Assistant\Provider\Gearman;

use GearmanClient;
use Revolve\Assistant\Client\Client as AbstractClient;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Connection\ConnectionTrait;
use Revolve\Assistant\Exception\ConnectionException;
use Revolve\Assistant\Task\TaskInterface;

class Client extends AbstractClient implements ConnectionInterface
{
    use ConnectionTrait;

    /**
     * @var GearmanClient
     */
    protected $client;

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function handle(TaskInterface $task)
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        $id = $this->client->doBackground($namespace, serialize($task));

        $task->setId($id);

        $this->tasks->attach($task);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCompleted(TaskInterface $task)
    {
        $status = $this->client->jobStatus($task->getId());

        return $status[0] === false;
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if (!$this->isConnected()) {
            $servers = $this->getServers();

            $this->client = new GearmanClient();
            $this->client->addServers($servers);

            $this->isConnected = true;
        }

        return $this;
    }

    /**
     * return string
     */
    protected function getServers()
    {
        $servers = $this->config["servers"];

        $strings = [];

        foreach ($servers as $server) {
            $strings[] = implode(":", $server);
        }

        return implode(",", $strings);
    }

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->client = null;
        }

        return $this;
    }
}
