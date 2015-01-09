<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Provider\Gearman\Worker;

$worker = new Worker([
    "namespace" => "assistant",
    "servers" => [
        ["127.0.0.1", 4730],
    ],
]);

$worker->connect();

$worker->run();
