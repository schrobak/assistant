<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Make;

$make = new Make();

$client = $make->client([
    "provider" => "gearman",
    "gearman" => [
        "servers" => [
            ["127.0.0.1", 4730],
        ],
        "namespace" => "assistant",
    ]
]);

$start = microtime(true);

foreach (range(1, 1000) as $tick) {
    $task = $make->task([
        "provider" => "gearman",
        "gearman" => [
            "closure" => function () {
                print "profile";
            },
        ],
    ]);

    $client->handle($task);
}

print "time taken: " . (round(microtime(true) - $start, 3) * 1000) . "ms\n";