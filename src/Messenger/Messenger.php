<?php

namespace Revolve\Assistant\Messenger;

use Revolve\Assistant\Config\ConfigInterface;
use Revolve\Assistant\Config\ConfigTrait;

abstract class Messenger implements MessengerInterface, ConfigInterface
{
    use ConfigTrait;
}
