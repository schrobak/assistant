<?php

namespace Revolve\Assistant\Provider\Memcached;

use Doctrine\Common\Cache\MemcachedCache;
use Memcached;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Connection\ConnectionTrait;
use Revolve\Assistant\Exception\ConnectionException;
use Revolve\Assistant\Messenger\CacheMessenger;
use Revolve\Container\ContainerAwareInterface;
use Revolve\Container\ContainerAwareTrait;

class Messenger extends CacheMessenger implements ConnectionInterface, ContainerAwareInterface
{
    use ConnectionTrait;
    use ContainerAwareTrait;

    /**
     * @var Memcached
     */
    protected $memcached;

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->memcached->quit();
            $this->memcached = null;

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
            $servers = $this->config["servers"];
            $namespace = $this->config["namespace"];

            $this->memcached = new Memcached();
            $this->memcached->addServers($servers);

            $this->cache = new MemcachedCache();
            $this->cache->setMemcached($this->memcached);
            $this->cache->setNamespace($namespace);

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

        return parent::read();
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function write($message)
    {
        $this->ensureConnected();

        return parent::write($message);
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConnectionException
     */
    public function remove($message)
    {
        $this->ensureConnected();

        return parent::remove($message);
    }
}
