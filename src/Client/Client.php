<?php

namespace Revolve\Assistant\Client;

use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Config\ConfigTrait;
use Revolve\Assistant\MakeAwareInterface;
use Revolve\Assistant\MakeAwareTrait;
use Revolve\Assistant\Messenger\MessengerInterface;
use SplObjectStorage;

abstract class Client implements ClientInterface, ConfigInterface, MakeAwareInterface
{
    use ConfigTrait;
    use MakeAwareTrait;

    /**
     * @var SplObjectStorage
     */
    protected $tasks;

    /**
     * @var array
     */
    protected $emitted = [];

    /**
     * @param null|array $config
     */
    public function __construct(array $config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }

        $this->tasks = $this->make()->object("SplObjectStorage");
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return $this
     */
    public function read(MessengerInterface $messenger)
    {
        foreach ($messenger->read() as $message) {
            if (in_array($message, $this->emitted)) {
                continue;
            }

            $unpacked = unserialize($message);

            foreach ($this->tasks as $task) {
                if ($unpacked[0] == $task->getId()) {
                    call_user_func_array([$task, "emit"], array_slice($unpacked, 1));

                    $this->emitted[] = $message;
                }
            }
        }

        if (count($this->emitted) > 100) {
            $this->emitted = array_slice($this->emitted, count($this->emitted) - 100);
        }

        return $this;
    }
}
