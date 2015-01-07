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

use Revolve\Assistant\Task\GearmanTask;

$task = new GearmanTask(function() use ($messenger) {
    print "one-way task\n";
});

use Revolve\Assistant\Client\GearmanClient;

$client = new GearmanClient([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
]);

$client->connect();

$client->handle($task);