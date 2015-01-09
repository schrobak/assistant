<?php

namespace Revolve\Assistant\Worker;

use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Config\ConfigTrait;

abstract class Worker implements WorkerInterface, ConfigInterface
{
    use ConfigTrait;
}
