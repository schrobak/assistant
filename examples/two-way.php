<?php

// For this to work, you need to have memcached and worker.php running

require "../vendor/autoload.php";

use League\Event\Event;
use Revolve\Assistant\Client\GearmanClient;
use Revolve\Assistant\Messenger\Cache\MemcachedCacheMessenger;
use Revolve\Assistant\Task\GearmanTask;
use Revolve\Assistant\Task\TaskInterface;

$messenger = new MemcachedCacheMessenger([
    "servers" => [
        ["127.0.0.1", 11211],
    ],
    "namespace" => "assistant",
]);

$messenger->connect();

$task = new GearmanTask(function (TaskInterface $task) use ($messenger) {
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

$client = new GearmanClient([
    "servers" => [
        ["127.0.0.1", 4730],
    ],
    "namespace" => "assistant",
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
