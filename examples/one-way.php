<?php

// For this to work, you need to have memcached and worker.php running

require "../vendor/autoload.php";

use Revolve\Assistant\Provider\Gearman\Client;
use Revolve\Assistant\Provider\Gearman\Task;
use Revolve\Assistant\Provider\Memcached\Messenger;

$messenger = new Messenger([
    "servers" => [
        ["127.0.0.1", 11211],
    ],
    "namespace" => "assistant",
]);

$messenger->connect();

$task = new Task(function () use ($messenger) {
    print "one-way task\n";
});

$client = new Client([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
]);

$client->connect();

$client->handle($task);
