<?php

namespace Revolve\Assistant\Test;

use Revolve\Assistant\Make;

class MakeTest extends Test
{
    /**
     * @test
     */
    public function it_makes_clients()
    {
        $make = new Make();

        $make->swap("GearmanClient", function () {
            $client = $this->mock("GearmanClient");
            $client->shouldReceive("addServers");

            return $client;
        });

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                "namespace" => "foo",
                "servers" => [
                    ["foo", 123],
                ],
            ],
        ];

        foreach ($providers as $provider) {
            $config["provider"] = $provider;

            $client = $make->client($config);

            $this->assertInstanceOf(
                "Revolve\\Assistant\\Client\\ClientInterface",
                $client
            );
        }

        $this->setExpectedException("Revolve\\Assistant\\Exception\\ProviderException");

        $make->client(["provider" => "foo"]);
    }

    /**
     * @test
     */
    public function it_makes_workers()
    {
        $make = new Make();

        $make->swap("GearmanWorker", function () {
            $worker = $this->mock("GearmanWorker");
            $worker->shouldReceive("addServers");

            return $worker;
        });

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                "namespace" => "foo",
                "servers" => [
                    ["foo", 123],
                ],
            ],
        ];

        foreach ($providers as $provider) {
            $config["provider"] = $provider;

            $client = $make->worker($config);

            $this->assertInstanceOf(
                "Revolve\\Assistant\\Worker\\WorkerInterface",
                $client
            );
        }

        $this->setExpectedException("Revolve\\Assistant\\Exception\\ProviderException");

        $make->worker(["provider" => "foo"]);
    }

    /**
     * @test
     */
    public function it_makes_messengers()
    {
        $make = new Make();

        $make->swap("Memcached", function () {
            $messenger = $this->mock("Memcached");
            $messenger->shouldReceive("addServers");

            return $messenger;
        });

        $make->swap("Revolve\\Assistant\\Provider\\Iron\\IronMQ", function () {
            $messenger = $this->mock("Revolve\\Assistant\\Provider\\Iron\\IronMQ");
            $messenger->shouldReceive("setConfig");

            return $messenger;
        });

        $providers = [
            "memcached",
            "iron",
        ];

        $config = [
            "memcached" => [
                "namespace" => "foo",
                "servers" => [
                    ["foo", 123],
                ],
            ],
            "iron" => [
                "namespace" => "foo",
                "token" => "",
                "project" => "",
            ],
        ];

        foreach ($providers as $provider) {
            $config["provider"] = $provider;

            $client = $make->messenger($config);

            $this->assertInstanceOf(
                "Revolve\\Assistant\\Messenger\\MessengerInterface",
                $client
            );
        }

        $this->setExpectedException("Revolve\\Assistant\\Exception\\ProviderException");

        $make->messenger(["provider" => "foo"]);
    }

    /**
     * @test
     */
    public function it_makes_tasks()
    {
        $make = new Make();

        $make->swap("GearmanTask", function () {
            $task = $this->mock("GearmanTask");

            return $task;
        });

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                "namespace" => "foo",
                "closure" => function() {
                    print "hello";
                },
            ],
        ];

        foreach ($providers as $provider) {
            $config["provider"] = $provider;

            $client = $make->task($config);

            $this->assertInstanceOf(
                "Revolve\\Assistant\\Task\\TaskInterface",
                $client
            );
        }

        $this->setExpectedException("Revolve\\Assistant\\Exception\\ProviderException");

        $make->task(["provider" => "foo"]);
    }
}
