<?php

namespace Revolve\Assistant;

use League\Event\EmitterInterface;

trait EmitterTrait
{
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
        return call_user_func_array([$this->getEmitter(), $method], $parameters);
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

    /**
     * @return EmitterInterface
     */
    abstract public function getEmitter();
}
