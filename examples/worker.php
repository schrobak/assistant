<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Provider\Gearman\Worker;

$worker = new Worker([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
]);

$worker->connect();

$worker->run();
