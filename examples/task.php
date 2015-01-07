<?php

require "../vendor/autoload.php";

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
    print "hi!";
});

// var_dump($task->getCode());
// var_dump($task->getVariables());
//
// $variables = $task->getVariables();
//
// $messenger = $variables["messenger"];
// var_dump($messenger->read());
