<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Make;

$make = new Make();

$worker = $make->worker([
    "provider" => "gearman",
    "gearman" => [
        "servers" => [
            ["127.0.0.1", 4730],
        ],
        "namespace" => "assistant",
    ],
]);

$worker->run();
