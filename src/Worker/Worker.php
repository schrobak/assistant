<?php

namespace Revolve\Assistant\Worker;

use Revolve\Assistant\ConfigTrait;

abstract class Worker implements WorkerInterface
{
    use ConfigTrait;
}
