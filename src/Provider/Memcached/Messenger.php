<?php

namespace Revolve\Assistant\Provider\Memcached;

use Memcached;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Connection\ConnectionTrait;
use Revolve\Assistant\Exception\ConnectionException;
use Revolve\Assistant\Messenger\CacheMessenger;

class Messenger extends CacheMessenger implements ConnectionInterface
{
    use ConnectionTrait;

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

            $this->memcached = $this->make()->object("Memcached");
            $this->memcached->addServers($servers);

            $this->cache = $this->make()->object("Doctrine\\Common\\Cache\\MemcachedCache");
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
