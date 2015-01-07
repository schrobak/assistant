<?php

namespace Revolve\Assistant\Messenger\Cache;

use Exception;
use Doctrine\Common\Cache\MemcachedCache;
use Memcached;
use Revolve\Assistant\ConnectionInterface;

class MemcachedCacheMessenger extends CacheMessenger implements ConnectionInterface
{
    /**
     * @var Memcached
     */
    protected $memcached;

    /**
     * @var bool
     */
    protected $isConnected = false;

    /**
     * {@inheritdoc}
     */
    public function disconnect()
    {
        if ($this->isConnected) {
            $this->memcached->quit();
            $this->isConnected = false;
        }
    }

    public function __sleep()
    {
        return ["config", "isConnected"];
    }

    public function __wakeup()
    {
        if ($this->isConnected) {
            $this->connect();
        }
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
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function read()
    {
        $this->checkConnection();

        return parent::read();
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
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function write($message)
    {
        $this->checkConnection();

        return parent::write($message);
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function remove($message)
    {
        $this->checkConnection();

        return parent::remove($message);
    }
}
