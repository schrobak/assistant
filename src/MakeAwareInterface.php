<?php

namespace Revolve\Assistant;

interface MakeAwareInterface
{
    /**
     * @param MakeInterface $make
     *
     * @return $this
     */
    public function setMake(MakeInterface $make);
}
