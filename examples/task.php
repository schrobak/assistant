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
    print "hi!";
});

// var_dump($task->getCode());
// var_dump($task->getVariables());
//
// $variables = $task->getVariables();
//
// $messenger = $variables["messenger"];
// var_dump($messenger->read());