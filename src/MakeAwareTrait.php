<?php

namespace Revolve\Assistant;

trait MakeAwareTrait
{
    /**
     * @var MakeInterface
     */
    protected $make;

    /**
     * @param MakeInterface $make
     *
     * @return $this
     */
    public function setMake(MakeInterface $make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return MakeInterface
     */
    protected function make()
    {
        if (!$this->make) {
            $this->make = new Make();
        }

        return $this->make;
    }
}