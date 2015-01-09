<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Make;

$make = new Make();

$task = $make->task([
    "provider" => "gearman",
    "gearman" => [
        "closure" => function () {
            print "one-way task\n";
        },
    ],
]);

$client = $make->client([
    "provider" => "gearman",
    "gearman" => [
        "namespace" => "assistant",
        "servers" => [
            ["127.0.0.1", 4730],
        ],
    ],
]);

$client->handle($task);
