<?php

return [
    "Doctrine\\Common\\Cache\\MemcachedCache" => function () {
        return new Doctrine\Common\Cache\MemcachedCache();
    },
    "GearmanClient" => function () {
        return new GearmanClient();
    },
    "GearmanTask" => function () {
        return new GearmanTask();
    },
    "GearmanWorker" => function () {
        return new GearmanWorker();
    },
    "Memcached" => function () {
        return new Memcached();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Client" => function () {
        return new Revolve\Assistant\Provider\Gearman\Client();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Task" => function () {
        return new Revolve\Assistant\Provider\Gearman\Task();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Worker" => function () {
        return new Revolve\Assistant\Provider\Gearman\Worker();
    },
    "Revolve\\Assistant\\Provider\\Iron\\IronMQ" => function () {
        return new Revolve\Assistant\Provider\Iron\IronMQ();
    },
    "Revolve\\Assistant\\Provider\\Iron\\Messenger" => function () {
        return new Revolve\Assistant\Provider\Iron\Messenger();
    },
    "Revolve\\Assistant\\Provider\\Memcached\\Messenger" => function () {
        return new Revolve\Assistant\Provider\Memcached\Messenger();
    },
    "SplObjectStorage" => function () {
        return new SplObjectStorage();
    },
    "League\\Event\\PriorityEmitter" => function () {
        return new League\Event\PriorityEmitter();
    }
];
