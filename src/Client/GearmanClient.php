<?php

namespace Revolve\Assistant\Client;

use GearmanClient as BaseClient;
use Revolve\Assistant\ConnectionInterface;
use Revolve\Assistant\ConnectionTrait;
use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\TaskInterface;
use SplObjectStorage;

class GearmanClient extends Client implements ConnectionInterface
{
    use ConnectionTrait;

    /**
     * @var GearmanClient
     */
    protected $client;

    /**
     * @var SplObjectStorage
     */
    protected $tasks;

    /**
     * @var array
     */
    protected $emitted = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);

        $this->tasks = new SplObjectStorage();
    }

    /**
     * {@inheritdoc}
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
     * @param MessengerInterface $messenger
     *
     * @return $this
     */
    public function read(MessengerInterface $messenger)
    {
        foreach ($messenger->read() as $message) {
            if (in_array($message, $this->emitted)) {
                continue;
            }

            $unpacked = unserialize($message);

            foreach ($this->tasks as $task) {
                if ($unpacked[0] == $task->getId()) {
                    call_user_func_array([$task, "emit"], array_slice($unpacked, 1));

                    $this->emitted[] = $message;
                }
            }
        }

        if (count($this->emitted) > 100) {
            $this->emitted = array_slice($this->emitted, count($this->emitted) - 100);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if (!$this->isConnected()) {
            $servers = $this->getServers();

            $this->client = new BaseClient();
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
