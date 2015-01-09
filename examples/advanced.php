<?php

require "../vendor/autoload.php";

use League\Event\Event;
use Revolve\Assistant\Make;
use Revolve\Assistant\Task\TaskInterface;

$make = new Make();

$messenger = $make->messenger([
    "provider" => "memcached",
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

$task = $make->task([
    "provider" => "gearman",
    "gearman" => [
        "closure" => function (TaskInterface $task) use ($messenger) {
            $task->writeTo($messenger, "start", time());

            print "writing start\n";

            foreach (range(1, 5) as $tick) {
                sleep(1);

                $task->writeTo($messenger, "tick", $tick);

                print "writing tick {$tick}\n";
            }

            $task->writeTo($messenger, "complete", time());

            print "writing complete\n";
        },
    ],
]);

$task->addListener("start", function (Event $event, $time) {
    print "started two-way at: {$time}\n";
});

$task->addListener("tick", function (Event $event, $tick) {
    print "two-way tick: {$tick}\n";
});

$task->addListener("complete", function (Event $event, $time) {
    print "completed two-way at: {$time}\n";
});

$client = $make->client([
    "provider" => "gearman",
    "gearman" => [
        "namespace" => "assistant",
        "servers" => [
            ["127.0.0.1", 4730],
        ],
    ]
]);

$client->handle($task);

do {
    $client->read($messenger);

    print ".";

    if ($client->hasCompleted($task)) {
        break;
    }

    usleep(50000);
} while (true);
