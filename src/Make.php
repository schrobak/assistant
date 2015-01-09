<?php

namespace Revolve\Assistant;

use ReflectionClass;
use Revolve\Assistant\Client\ClientInterface;
use Revolve\Assistant\Exception\ProviderException;
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
        $bindings = require __DIR__."/bindings.php";

        foreach ($bindings as $key => $factory) {
            $container->bind($key, $factory);
        }
    }

    /**
     * @param array $config
     *
     * @return ClientInterface
     *
     * @throws ProviderException
     */
    public function client(array $config)
    {
        return $this->provider($this->clients, $config);
    }

    /**
     * @param array $providers
     * @param array $config
     *
     * @return mixed
     *
     * @throws ProviderException
     */
    protected function provider(array $providers, array $config)
    {
        $type = $config["provider"];

        foreach ($providers as $key => $value) {
            if ($type === $key) {
                $provider = $this->container->resolve($value);

                $reflection = new ReflectionClass($provider);

                if ($this->isContainer($reflection)) {
                    $provider->setContainer($this->container);
                }

                if ($this->isConfig($reflection)) {
                    $provider->setConfig($config[$type]);
                }

                if ($this->isCallback($reflection)) {
                    $provider->setCallback($config[$type]["callback"]);
                }
                if ($this->isConnection($reflection)) {
                    $provider->connect();
                }

                return $provider;
            }
        }

        $title = ucfirst($type);

        throw new ProviderException("{$title} provider not recognised");
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isContainer(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Container\\ContainerAwareInterface"
        );
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isConfig(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Config\\ConfigInterface"
        );
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isCallback(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Callback\\CallbackInterface"
        );
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isConnection(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Connection\\ConnectionInterface"
        );
    }

    /**
     * @param array $config
     *
     * @return TaskInterface
     *
     * @throws ProviderException
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
     * @throws ProviderException
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
     * @throws ProviderException
     */
    public function messenger(array $config)
    {
        return $this->provider($this->messengers, $config);
    }
}
