<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Make;

$make = new Make();

$messenger = $make->messenger([
    "provider" => "memcached",
    "memcached" => [
        "namespace" => "assistant",
        "servers" => [
            ["127.0.0.1", 11211],
        ],
    ],
]);

$task = $make->task([
    "provider" => "gearman",
    "gearman" => [
        "closure" => function () use ($messenger) {
            print "hi!";
        },
    ],
]);

 var_dump($task->getCode());
 var_dump($task->getVariables());

 $variables = $task->getVariables();

 $messenger = $variables["messenger"];
 var_dump($messenger->read());
