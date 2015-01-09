<?php

namespace Revolve\Assistant;

use Closure;
use ReflectionClass;
use Revolve\Assistant\Closure\ClosureInterface;
use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Exception\ProviderException;

class Make implements MakeInterface
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
     * @var array
     */
    protected $bound = [];

    /**
     * {@inheritdoc}
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

        foreach ($providers as $alias => $class) {
            if ($type === $alias) {
                $provider = $this->object($class);

                $reflection = new ReflectionClass($provider);

                if ($this->isConfigInterface($reflection)) {
                    /** @var $provider ConfigInterface */
                    $provider->setConfig($config[$type]);
                }

                if ($this->isClosureInterface($reflection)) {
                    /** @var $provider ClosureInterface */
                    $provider->setClosure($config[$type]["closure"]);
                }
                if ($this->isConnectionInterface($reflection)) {
                    /** @var $provider ConnectionInterface */
                    $provider->connect();
                }

                return $provider;
            }
        }

        throw new ProviderException("{$type} provider not recognised");
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isConfigInterface(ReflectionClass $reflection)
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
    protected function isClosureInterface(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Closure\\ClosureInterface"
        );
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isConnectionInterface(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\Connection\\ConnectionInterface"
        );
    }

    /**
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    protected function isMakeAwareInterface(ReflectionClass $reflection)
    {
        return $reflection->implementsInterface(
            "Revolve\\Assistant\\MakeAwareInterface"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function task(array $config)
    {
        return $this->provider($this->tasks, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function worker(array $config)
    {
        return $this->provider($this->workers, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function messenger(array $config)
    {
        return $this->provider($this->messengers, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function object($key)
    {
        $this->bind();

        $object = null;

        if (isset($this->bound[$key])) {
            $bound = $this->bound[$key];

            $object = $bound();

            $reflection = new ReflectionClass($object);

            if ($this->isMakeAwareInterface($reflection)) {
                /** @var MakeAwareInterface $object */
                $object->setMake($this);
            }
        }

        return $object;
    }

    protected function bind()
    {
        if (!$this->bound) {
            $this->bound = require __DIR__."/bindings.php";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function swap($key, Closure $factory)
    {
        $this->bind();

        $this->bound[$key] = $factory;

        return $this;
    }
}
