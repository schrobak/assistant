<?php

namespace Revolve\Assistant\Callback;

use Closure;

trait CallbackTrait
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param null|Closure $callback
     */
    public function __construct(Closure $callback = null)
    {
        if ($callback !== null) {
            $this->setCallback($callback);
        }
    }

    /**
     * @return Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCallback(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
