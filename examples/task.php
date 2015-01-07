<?php

require "../vendor/autoload.php";

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
    print "hi!";
});

// var_dump($task->getCode());
// var_dump($task->getVariables());
//
// $variables = $task->getVariables();
//
// $messenger = $variables["messenger"];
// var_dump($messenger->read());
