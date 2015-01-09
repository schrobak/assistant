<?php

require "../vendor/autoload.php";

use Revolve\Assistant\Make;

$make = new Make();

$messenger = $make->messenger([
    "provider" => "iron",
    "memcached" => [
        "namespace" => "assistant",
        "servers" => [
            ["127.0.0.1", 11211],
        ],
    ],
    "iron" => [
        "namespace" => "assistant",
        "token" => getenv("IRON_TOKEN"),
        "project" => getenv("IRON_PROJECT"),
    ],
]);

 $messenger = unserialize(serialize($messenger));

 var_dump($messenger->read());

 $messenger->write("foo");
 $messenger->write("bar");
 $messenger->write("baz");

 var_dump($messenger->read());

 $messenger->remove("foo");
 $messenger->remove("bar");
 $messenger->remove("baz");

 var_dump($messenger->read());

 $messenger->disconnect();
