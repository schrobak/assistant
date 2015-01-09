<?php

namespace Revolve\Assistant\Provider\Gearman;

use GearmanJob;
use GearmanWorker;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Connection\ConnectionTrait;
use Revolve\Assistant\Exception\ConnectionException;
use Revolve\Assistant\Task\TaskInterface;
use Revolve\Assistant\Worker\Worker as AbstractWorker;
use Revolve\Container\ContainerAwareInterface;
use Revolve\Container\ContainerAwareTrait;

class Worker extends AbstractWorker implements ConnectionInterface, ContainerAwareInterface
{
    use ConnectionTrait;
    use ContainerAwareTrait;

    /**
     * @var GearmanWorker
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
     *
     * @throws ConnectionException
     */
    public function run()
    {
        $this->ensureConnected();

        $namespace = $this->config["namespace"];

        $this->worker->addFunction($namespace, function (GearmanJob $job) {
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

            $this->worker = new GearmanWorker();
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
