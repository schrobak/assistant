<?php

namespace Revolve\Assistant\Task;

use Jeremeamia\SuperClosure\SerializableClosure;
use League\Event\PriorityEmitter;
use Revolve\Assistant\EmitterTrait;

abstract class Task extends SerializableClosure implements TaskInterface
{
    use EmitterTrait;

    /**
     * @var PriorityEmitter
     */
    protected $emitter;

    /**
     * @var string
     */
    protected $id = null;

    /**
     * @var callable
     */
    protected $closure;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @param callable $closure
     */
    public function __construct(callable $closure)
    {
        $this->closure = $closure;
        $this->emitter = new PriorityEmitter();
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
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }
}
