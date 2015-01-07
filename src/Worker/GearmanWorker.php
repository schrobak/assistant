<?php

namespace Revolve\Assistant\Worker;

use Exception;
use GearmanJob as BaseJob;
use GearmanWorker as BaseWorker;
use Revolve\Assistant\ConnectionInterface;
use Revolve\Assistant\Task\TaskInterface;

class GearmanWorker extends Worker implements ConnectionInterface
{
    /**
     * @var BaseWorker
     */
    protected $worker;

    /**
     * @var bool
     */
    protected $isConnected = false;

    /**
     * {@inheritdoc}
     */
    public function handle(TaskInterface $task)
    {
        $task($task);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->checkConnection();

        $namespace = $this->config["namespace"];

        $this->worker->addFunction($namespace, function (BaseJob $job) {
            $workload = $job->workload();

            /** @var TaskInterface $closure */
            $task = unserialize($workload);

            $this->handle($task);
        });

        while ($this->worker->work()) {
            ;
        }
    }

    /**
     * @throws Exception
     */
    protected function checkConnection()
    {
        $isConnection = is_subclass_of($this, "Revolve\\Assistant\\ConnectionInterface");

        if ($isConnection and !$this->isConnected()) {
            throw new Exception("You need to connect first!");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if (!$this->isConnected) {
            $servers = $this->getServers();

            $this->worker = new BaseWorker();
            $this->worker->addServers($servers);

            $this->isConnected = true;
        }
    }

    /**
     * @return string
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
        if ($this->isConnected) {
            $this->worker = null;
            $this->isConnected = false;
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
