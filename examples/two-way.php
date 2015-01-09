<?php

// For this to work, you need to have memcached and worker.php running

require "../vendor/autoload.php";

use League\Event\Event;
use Revolve\Assistant\Provider\Gearman\Client;
use Revolve\Assistant\Provider\Gearman\Task;
use Revolve\Assistant\Provider\Memcached\Messenger;
use Revolve\Assistant\Task\TaskInterface;

$messenger = new Messenger([
    "namespace" => "assistant",
    "servers" => [
        ["127.0.0.1", 11211],
    ],
]);

$messenger->connect();

$task = new Task(function (TaskInterface $task) use ($messenger) {
    $task->writeTo($messenger, "start", time());

    print "writing start\n";

    foreach (range(1, 5) as $tick) {
        sleep(1);

        $task->writeTo($messenger, "tick", $tick);

        print "writing tick {$tick}\n";
    }

    $task->writeTo($messenger, "complete", time());

    print "writing complete\n";
});

$task->addListener("start", function (Event $event, $time) {
    print "started two-way at: {$time}\n";
});

$task->addListener("tick", function (Event $event, $tick) {
    print "two-way tick: {$tick}\n";
});

$task->addListener("complete", function (Event $event, $time) {
    print "completed two-way at: {$time}\n";
});

$client = new Client([
    "namespace" => "assistant",
    "servers" => [
        ["127.0.0.1", 4730],
    ],
]);

$client->connect();

$client->handle($task);

do {
    $client->read($messenger);

    print ".";

    if ($client->hasCompleted($task)) {
        break;
    }

    usleep(50000);
} while (true);
