<?php

namespace Revolve\Assistant\Messenger;

use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Config\ConfigTrait;
use Revolve\Assistant\MakeAwareInterface;
use Revolve\Assistant\MakeAwareTrait;

abstract class Messenger implements MessengerInterface, ConfigInterface, MakeAwareInterface
{
    use ConfigTrait;
    use MakeAwareTrait;
}
