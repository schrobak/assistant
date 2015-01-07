<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Worker\GearmanWorker;

$worker = new GearmanWorker([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
]);

$worker->connect();

$worker->run();
