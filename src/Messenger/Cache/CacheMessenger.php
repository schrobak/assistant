<?php

namespace Revolve\Assistant\Messenger\Cache;

use Doctrine\Common\Cache\Cache;
use Exception;
use Revolve\Assistant\Messenger\MessengerInterface;

abstract class CacheMessenger implements MessengerInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var string
     */
    protected $driver;

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $this->checkConnection();

        if ($keys = $this->cache->fetch("keys")) {
            return $this->fetchAllWith($keys);
        }

        return [];
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    protected function fetchAllWith(array $keys)
    {
        $items = [];

        foreach ($keys as $key) {
            $items[] = $this->cache->fetch($key);
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function write($message)
    {
        $this->checkConnection();

        $key = $this->getNewKey();

        $this->addKey($key);
        $this->saveMessage($key, $message);

        return $this;
    }

    /**
     * @return string
     */
    protected function getNewKey()
    {
        $this->id += 1;

        return "entry.{$this->id}";
    }

    /**
     * @param string $key
     * @param string $message
     *
     * @return string
     */
    protected function saveMessage($key, $message)
    {
        $this->cache->save($key, $message);

        return $key;
    }

    /**
     * @param string $key
     */
    protected function addKey($key)
    {
        $keys = $this->cache->fetch("keys");

        $keys[] = $key;

        $this->cache->save("keys", $keys);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($message)
    {
        $this->checkConnection();

        $keys = [];

        if ($stored = $this->cache->fetch("keys")) {
            $keys = $stored;
        }

        $new = [];

        foreach ($keys as $key) {
            $item = $this->cache->fetch($key);

            if ($item === $message) {
                $this->cache->delete($key);
            } else {
                $new[] = $key;
            }
        }

        $this->cache->save("keys", $new);

        return $this;
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
}
