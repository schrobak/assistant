<?php

use Revolve\Assistant\Make;
use Revolve\Assistant\Provider\Gearman;
use Revolve\Assistant\Provider\Iron;
use Revolve\Assistant\Provider\Memcached;

return [
    "Revolve\\Assistant\\Make" => function () {
        return new Make();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Client" => function () {
        return new Gearman\Client();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Worker" => function () {
        return new Gearman\Worker();
    },
    "Revolve\\Assistant\\Provider\\Gearman\\Task" => function () {
        return new Gearman\Task();
    },
    "Revolve\\Assistant\\Provider\\Iron\\Messenger" => function () {
        return new Iron\Messenger();
    },
    "Revolve\\Assistant\\Provider\\Memcached\\Messenger" => function () {
        return new Memcached\Messenger();
    },
    "Gearman\\Client" => function () {
        return new GearmanClient();
    },
    "Gearman\\Task" => function () {
        return new GearmanTask();
    },
    "Gearman\\Job" => function () {
        return new GearmanJob();
    },
    "Gearman\\Worker" => function () {
        return new GearmanWorker();
    },
    "Memcached" => function () {
        return new Memcached();
    },
    "Doctrine\\Common\\Cache\\MemcachedCache" => function () {
        return new Doctrine\Common\Cache\MemcachedCache();
    },
    "IronMQ" => function () {
        return new IronMQ();
    },
];
