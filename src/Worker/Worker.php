<?php

namespace Revolve\Assistant\Worker;

use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Config\ConfigTrait;
use Revolve\Assistant\MakeAwareInterface;
use Revolve\Assistant\MakeAwareTrait;

abstract class Worker implements WorkerInterface, ConfigInterface, MakeAwareInterface
{
    use ConfigTrait;
    use MakeAwareTrait;
}
