<?php

namespace Revolve\Assistant\Worker;

use GearmanJob as BaseJob;
use GearmanWorker as BaseWorker;
use Revolve\Assistant\ConnectionInterface;
use Revolve\Assistant\ConnectionTrait;
use Revolve\Assistant\Task\TaskInterface;

class GearmanWorker extends Worker implements ConnectionInterface
{
    use ConnectionTrait;

    /**
     * @var BaseWorker
     */
    protected $worker;

    /**
     * {@inheritdoc}
     */
    public function handle(TaskInterface $task)
    {
        return $task($task);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        $this->worker->addFunction($namespace, function (BaseJob $job) {
            $workload = $job->workload();

            /** @var TaskInterface $closure */
            $task = unserialize($workload);

            $task->setJob($job);

            $this->handle($task);
        });

        while ($this->worker->work()) {
            // do nothing here
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

            $this->worker = new BaseWorker();
            $this->worker->addServers($servers);

            $this->isConnected = true;
        }

        return $this;
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
        if ($this->isConnected()) {
            $this->worker = null;
            $this->isConnected = false;
        }

        return $this;
    }
}
