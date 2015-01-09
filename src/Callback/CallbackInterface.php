<?php

namespace Revolve\Assistant\Callback;

use Closure;

interface CallbackInterface
{
    /**
     * @return Closure
     */
    public function getCallback();

    /**
     * @param Closure $callback
     *
     * @return $this
     */
    public function setCallback(Closure $callback);
}
