<?php

require "vendor/autoload.php";

use Revolve\Assistant\Messenger\Cache\MemcachedCacheMessenger;

$messenger = new MemcachedCacheMessenger([
    "servers" => [
        ["127.0.0.1", 11211],
    ],
    "namespace" => "assistant"
]);

$messenger->connect();

var_dump($messenger->read());

$messenger->write("foo");

var_dump($messenger->read());

$messenger->remove("foo");

var_dump($messenger->read());

$messenger->disconnect();