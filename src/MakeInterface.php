<?php

namespace Revolve\Assistant;

use Closure;
use Revolve\Assistant\Client\ClientInterface;
use Revolve\Assistant\Exception\ProviderException;
use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\TaskInterface;
use Revolve\Assistant\Worker\WorkerInterface;

interface MakeInterface
{
    /**
     * @param array $config
     *
     * @return ClientInterface
     *
     * @throws ProviderException
     */
    public function client(array $config);

    /**
     * @param array $config
     *
     * @return TaskInterface
     *
     * @throws ProviderException
     */
    public function task(array $config);

    /**
     * @param array $config
     *
     * @return WorkerInterface
     *
     * @throws ProviderException
     */
    public function worker(array $config);

    /**
     * @param array $config
     *
     * @return MessengerInterface
     *
     * @throws ProviderException
     */
    public function messenger(array $config);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function object($key);

    /**
     * @param string  $key
     * @param Closure $factory
     *
     * @return $this
     */
    public function swap($key, Closure $factory);
}
