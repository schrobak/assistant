<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Messenger\Cache\MemcachedCacheMessenger;

$messenger = new MemcachedCacheMessenger([
    "servers" => [
        ["127.0.0.1", 11211],
    ],
    "namespace" => "assistant"
]);

$messenger->connect();

// $messenger = unserialize(serialize($messenger));
//
// var_dump($messenger->read());
//
// $messenger->write("foo");
// $messenger->write("bar");
// $messenger->write("baz");
//
// var_dump($messenger->read());
//
// $messenger->remove("foo");
// $messenger->remove("bar");
// $messenger->remove("baz");
//
// var_dump($messenger->read());
//
// $messenger->disconnect();