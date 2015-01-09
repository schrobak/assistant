<?php

namespace Revolve\Assistant;

use Exception;
use ReflectionClass;
use Revolve\Assistant\Client\ClientInterface;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\TaskInterface;
use Revolve\Assistant\Worker\WorkerInterface;
use Revolve\Container\Container;
use Revolve\Container\ContainerAwareInterface;
use Revolve\Container\ContainerAwareTrait;
use Revolve\Container\ContainerInterface;

class Make implements ContainerAwareInterface
{
    use ContainerAwareTrait;

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
     * @var bool
     */
    protected $isBound = false;

    /**
     * @param null|ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        if ($container === null) {
            $container = new Container();
            $this->bind($container);
        }

        $this->container = $container;
    }

    /**
     * @param ContainerInterface $container
     */
    protected function bind(ContainerInterface $container)
    {
        $bindings = require(__DIR__ . "/bindings.php");

        foreach ($bindings as $key => $factory) {
            $container->bind($key, $factory);
        }
    }

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
     *
     * @throws Exception
     */
    protected function provider(array $providers, array $config, $isTask = false)
    {
        $type = $config["provider"];

        foreach ($providers as $key => $value) {
            if ($type === $key) {
                $provider = $this->container->resolve($value);

                $reflection = new ReflectionClass($provider);

                $isConfig = $reflection->implementsInterface(
                    "Revolve\\Assistant\\Config\\ConfigInterface"
                );

                if ($isConfig) {
                    $provider->setConfig($config[$type]);
                }

                $isCallback = $reflection->implementsInterface(
                    "Revolve\\Assistant\\Callback\\CallbackInterface"
                );

                if ($isCallback) {
                    $provider->setCallback($config[$type]["callback"]);
                }

                $isConnection = $reflection->implementsInterface(
                    "Revolve\\Assistant\\Connection\\ConnectionInterface"
                );

                if ($isConnection) {
                    $provider->connect();
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
