<?php

namespace Revolve\Assistant;

use Exception;
use ReflectionClass;
use Revolve\Assistant\Client\ClientInterface;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\TaskInterface;
use Revolve\Assistant\Worker\WorkerInterface;

class Make
{
    /**
     * @var array
     */
    protected $clients = [
        "gearman" => "Revolve\\Assistant\\Provider\\Gearman\\Client",
    ];

    /**
     * @var array
     */
    protected $tasks = [
        "gearman" => "Revolve\\Assistant\\Provider\\Gearman\\Task",
    ];

    /**
     * @var array
     */
    protected $workers = [
        "gearman" => "Revolve\\Assistant\\Provider\\Gearman\\Worker",
    ];

    /**
     * @var array
     */
    protected $messengers = [
        "iron" => "Revolve\\Assistant\\Provider\\Iron\\Messenger",
        "memcached" => "Revolve\\Assistant\\Provider\\Memcached\\Messenger",
    ];

    /**
     * @param array $config
     *
     * @return ClientInterface
     *
     * @throws Exception
     */
    public function client(array $config)
    {
        return $this->provider($this->clients, $config);
    }

    /**
     * @param array $providers
     * @param array $config
     * @param bool  $isTask
     *
     * @return mixed
     * @throws \Exception
     */
    protected function provider(array $providers, array $config, $isTask = false)
    {
        $type = $config["provider"];

        foreach ($providers as $key => $value) {
            if ($type === $key) {
                if ($isTask) {
                    $provider = new $value($config[$type]["callback"]);
                } else {
                    $provider = new $value($config[$type]);

                    $reflection = new ReflectionClass($provider);

                    $isConnection = $reflection->implementsInterface(
                        "Revolve\\Assistant\\Connection\\ConnectionInterface"
                    );

                    if ($isConnection) {
                        /** @var ConnectionInterface $provider */
                        $provider->connect();
                    }
                }

                return $provider;
            }
        }

        throw new Exception("Unrecognised provider: {$type}");
    }

    /**
     * @param array $config
     *
     * @return TaskInterface
     *
     * @throws Exception
     */
    public function task(array $config)
    {
        return $this->provider($this->tasks, $config, true);
    }

    /**
     * @param array $config
     *
     * @return WorkerInterface
     *
     * @throws Exception
     */
    public function worker(array $config)
    {
        return $this->provider($this->workers, $config);
    }

    /**
     * @param array $config
     *
     * @return MessengerInterface
     *
     * @throws Exception
     */
    public function messenger(array $config)
    {
        return $this->provider($this->messengers, $config);
    }
}
