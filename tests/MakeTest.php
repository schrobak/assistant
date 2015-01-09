<?php

namespace Revolve\Assistant\Test;

use ReflectionClass;
use Revolve\Assistant\Connection\ConnectionInterface;
use Revolve\Assistant\Make;

class MakeTest extends Test
{
    /**
     * @var array
     */
    protected $clients = [
        "gearman",
    ];

    /**
     * @var array
     */
    protected $tasks = [
        "gearman",
    ];

    /**
     * @var array
     */
    protected $workers = [
        "gearman",
    ];

    /**
     * @var array
     */
    protected $messengers = [
        "iron",
        "memcached",
    ];

    /**
     * @var array
     */
    protected $config = [
        "gearman" => [
            "namespace" => "assistant",
            "servers" => [
                ["127.0.0.1", 4730],
            ],
        ],
        "memcached" => [
            "namespace" => "assistant",
            "servers" => [
                ["127.0.0.1", 11211],
            ],
        ],
        "iron" => [
            "namespace" => "assistant",
        ],
    ];

    public function setUp()
    {
        $this->config["gearman"]["callback"] = function () {
            print "hello";
        };

        $this->config["iron"]["token"] = getenv("IRON_TOKEN");
        $this->config["iron"]["project"] = getenv("IRON_PROJECT");
    }

    /**
     * @test
     */
    public function it_makes_and_connects_clients()
    {
        $this->it_makes_and_connects(
            $this->clients, "client", "Revolve\\Assistant\\Client\\ClientInterface"
        );
    }

    /**
     * @param array  $providers
     * @param string $method
     * @param string $interface
     */
    protected function it_makes_and_connects(array $providers, $method, $interface)
    {
        $make = new Make();

        foreach ($providers as $provider) {
            $config = $this->config;
            $config["provider"] = $provider;

            $client = $make->{$method}($config);

            $this->assertInstanceOf($interface, $client);

            $reflection = new ReflectionClass($client);

            if ($reflection->implementsInterface("Revolve\\Assistant\\Connection\\ConnectionInterface")) {
                /** @var $client ConnectionInterface */
                $this->assertTrue($client->isConnected());
            }
        }
    }

    /**
     * @test
     */
    public function it_makes_and_connects_workers()
    {
        $this->it_makes_and_connects(
            $this->workers, "worker", "Revolve\\Assistant\\Worker\\WorkerInterface"
        );
    }

    /**
     * @test
     */
    public function it_makes_and_connects_tasks()
    {
        $this->it_makes_and_connects(
            $this->tasks, "task", "Revolve\\Assistant\\Task\\TaskInterface"
        );
    }

    /**
     * @test
     */
    public function it_makes_and_connects_messengers()
    {
        $this->it_makes_and_connects(
            $this->messengers, "messenger", "Revolve\\Assistant\\Messenger\\MessengerInterface"
        );
    }

    /**
     * @test
     */
    public function it_throws_exception_when_client_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("client");
    }

    /**
     * @param string $method
     */
    protected function it_throws_exception_when_not_found($method)
    {
        $this->setExpectedException("Exception");

        $make = new Make();

        $make->{$method}([
            "provider" => "foo",
        ]);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_worker_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("worker");
    }

    /**
     * @test
     */
    public function it_throws_exception_when_task_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("task");
    }

    /**
     * @test
     */
    public function it_throws_exception_when_messenger_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("messenger");
    }
}
