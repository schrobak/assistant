<?php

// For this to work, you need to have memcached and worker.php running

require "../vendor/autoload.php";

use Revolve\Assistant\Client\GearmanClient;
use Revolve\Assistant\Messenger\Cache\MemcachedCacheMessenger;
use Revolve\Assistant\Task\GearmanTask;

$messenger = new MemcachedCacheMessenger([
    "servers" => [
        ["127.0.0.1", 11211],
    ],
    "namespace" => "assistant",
]);

$messenger->connect();

$task = new GearmanTask(function () use ($messenger) {
    print "one-way task\n";
});

$client = new GearmanClient([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
]);

$client->connect();

$client->handle($task);
