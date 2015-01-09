<?php

namespace Revolve\Assistant\Closure;

use Closure as BaseClosure;

interface ClosureInterface
{
    /**
     * @return BaseClosure
     */
    public function getClosure();

    /**
     * @param BaseClosure $closure
     *
     * @return $this
     */
    public function setClosure(BaseClosure $closure);
}
