<?php

namespace Revolve\Assistant\Closure;

use Closure as BaseClosure;
use Jeremeamia\SuperClosure\SerializableClosure;

abstract class Closure extends SerializableClosure implements ClosureInterface
{
    /**
     * @param BaseClosure $closure
     */
    public function __construct(BaseClosure $closure = null)
    {
        if ($closure === null) {
            $closure = function () {
                print "hello";
            };
        }

        parent::__construct($closure);
    }

    /**
     * @return BaseClosure
     */
    public function getClosure()
    {
        return $this->closure;
    }

    /**
     * @param BaseClosure $closure
     *
     * @return $this
     */
    public function setClosure(BaseClosure $closure)
    {
        $this->closure = $closure;

        return $this;
    }
}
