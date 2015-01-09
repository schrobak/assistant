<?php

namespace Revolve\Assistant\Callback;

use Closure;
use Jeremeamia\SuperClosure\SerializableClosure;

abstract class Callback extends SerializableClosure
{
    /**
     * @param callable $closure
     *
     * @return $this
     */
    public function setClosure(Closure $closure)
    {
        $this->closure = $closure;

        return $this;
    }

    /**
     * @param callable $closure
     */
    public function __construct(Closure $closure = null)
    {
        if ($closure === null) {
            $closure = function () {
            };
        }

        parent::__construct($closure);
    }
}
