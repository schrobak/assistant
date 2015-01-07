<?php

namespace Revolve\Assistant\Messenger\Cache;

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
     * @return $this
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
     * @return $this
     */
    public function disconnect()
    {
        if ($this->isConnected) {
            $this->memcached->quit();
            $this->isConnected = false;
        }
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
