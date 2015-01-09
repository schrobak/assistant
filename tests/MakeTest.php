<?php

namespace Revolve\Assistant\Test;

use Exception;
use Revolve\Assistant\Make;
use Revolve\Container\Container;
use Revolve\Container\ContainerInterface;

class MakeTest extends Test
{
    /**
     * @test
     */
    public function it_makes_and_connects_clients()
    {
        $client = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Client");
        $client->shouldReceive("setConfig");
        $client->shouldReceive("connect");

        $container = new Container();
        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Client", function () use ($client) {
            return $client;
        });

        $make = $this->getNewMake($container);

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                // nothing to see...
            ],
        ];

        $this->it_makes_and_connects(
            $make, $providers, $config, "client",
            "Revolve\\Assistant\\Client\\ClientInterface"
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return Make
     */
    protected function getNewMake(ContainerInterface $container)
    {
        $make = new Make();
        $make->setContainer($container);

        return $make;
    }

    /**
     * @param Make   $make
     * @param array  $providers
     * @param array  $config
     * @param string $method
     * @param string $interface
     */
    protected function it_makes_and_connects(Make $make, array $providers, array $config, $method, $interface)
    {
        foreach ($providers as $provider) {
            $config["provider"] = $provider;

            $provider = $make->{$method}($config);

            $this->assertInstanceOf($interface, $provider);
        }
    }

    /**
     * @test
     */
    public function it_makes_and_connects_workers()
    {
        $worker = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Worker");
        $worker->shouldReceive("setConfig");
        $worker->shouldReceive("connect");

        $container = new Container();
        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Worker", function () use ($worker) {
            return $worker;
        });

        $make = $this->getNewMake($container);

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                // nothing to see...
            ],
        ];

        $this->it_makes_and_connects(
            $make, $providers, $config, "worker",
            "Revolve\\Assistant\\Worker\\WorkerInterface"
        );
    }

    /**
     * @test
     */
    public function it_makes_and_connects_tasks()
    {
        $task = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Task");
        $task->shouldReceive("setCallback");
        $task->shouldReceive("connect");

        $container = new Container();
        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Task", function () use ($task) {
            return $task;
        });

        $make = $this->getNewMake($container);

        $providers = [
            "gearman",
        ];

        $config = [
            "gearman" => [
                "callback" => function () {
                    print "hello";
                },
            ],
        ];

        $this->it_makes_and_connects(
            $make, $providers, $config, "task",
            "Revolve\\Assistant\\Task\\TaskInterface"
        );
    }

    /**
     * @test
     */
    public function it_makes_and_connects_messengers()
    {
        $memcached = $this->mock("Revolve\\Assistant\\Provider\\Memcached\\Messenger");
        $memcached->shouldReceive("setConfig");
        $memcached->shouldReceive("connect");

        $iron = $this->mock("Revolve\\Assistant\\Provider\\Iron\\Messenger");
        $iron->shouldReceive("setConfig");
        $iron->shouldReceive("connect");

        $container = new Container();

        $container->bind("Revolve\\Assistant\\Provider\\Memcached\\Messenger", function () use ($memcached) {
            return $memcached;
        });

        $container->bind("Revolve\\Assistant\\Provider\\Iron\\Messenger", function () use ($iron) {
            return $iron;
        });

        $make = $this->getNewMake($container);

        $providers = [
            "memcached",
            "iron",
        ];

        $config = [
            "memcached" => [
                // nothing to see...
            ],
            "iron" => [
                // nothing to see...
            ],
        ];

        $this->it_makes_and_connects(
            $make, $providers, $config, "messenger",
            "Revolve\\Assistant\\Messenger\\MessengerInterface"
        );
    }

    /**
     * @test
     *
     * @expectedException Exception
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
        $make = new Make();

        $make->{$method}([
            "provider" => "foo",
        ]);
    }

    /**
     * @test
     *
     * @expectedException Exception
     */
    public function it_throws_exception_when_worker_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("worker");
    }

    /**
     * @test
     *
     * @expectedException Exception
     */
    public function it_throws_exception_when_task_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("task");
    }

    /**
     * @test
     *
     * @expectedException Exception
     */
    public function it_throws_exception_when_messenger_provider_not_found()
    {
        $this->it_throws_exception_when_not_found("messenger");
    }
}
