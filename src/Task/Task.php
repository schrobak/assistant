<?php

namespace Revolve\Assistant\Task;

use Closure;
use League\Event\PriorityEmitter;
use Revolve\Assistant\Closure\Closure as AbstractClosure;
use Revolve\Assistant\Closure\ClosureInterface;
use Revolve\Assistant\MakeAwareInterface;
use Revolve\Assistant\MakeAwareTrait;

abstract class Task extends AbstractClosure implements TaskInterface, ClosureInterface, MakeAwareInterface
{
    use MakeAwareTrait;

    /**
     * @var PriorityEmitter
     */
    protected $emitter;

    /**
     * @var string
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @param null|Closure $callback
     */
    public function __construct(Closure $callback = null)
    {
        if ($callback !== null) {
            $this->setClosure($callback);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $this->cache();

        return $this->code;
    }

    protected function cache()
    {
        if (!$this->code) {
            list($this->code, $this->variables) = unserialize($this->serialize());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        $this->cache();

        return $this->variables;
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($event, $listener)
    {
        $this->forwardToEmitter("addListener", func_get_args());

        return $this;
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    protected function forwardToEmitter($method, array $parameters)
    {
        if (!$this->emitter) {
            $this->emitter = $this->make()->object("League\\Event\\PriorityEmitter");
        }

        return call_user_func_array([$this->emitter, $method], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function addOneTimeListener($event, $listener)
    {
        $this->forwardToEmitter("addOneTimeListener", func_get_args());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($event, $listener)
    {
        $this->forwardToEmitter("removeListener", func_get_args());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListeners($event)
    {
        $this->forwardToEmitter("removeAllListeners", func_get_args());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event)
    {
        return $this->forwardToEmitter("hasListeners", func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event)
    {
        return $this->forwardToEmitter("getListeners", func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function emit($event)
    {
        return $this->forwardToEmitter("emit", func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function emitBatch(array $events)
    {
        return $this->forwardToEmitter("emitBatch", func_get_args());
    }
}
