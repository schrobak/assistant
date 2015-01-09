<?php

namespace Revolve\Assistant\Test;

use Mockery\MockInterface;
use Revolve\Assistant\Make;
use Revolve\Container\Container;
use Revolve\Container\ContainerInterface;

class MakeTest extends Test
{
    /**
     * @test
     */
    public function it_makes_clients()
    {
        $container = new Container();

        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Client", function () {
            return $this->getNewGearmanClientMock();
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

        $this->it_makes(
            $make, $providers, $config, "client",
            "Revolve\\Assistant\\Client\\ClientInterface"
        );
    }

    /**
     * @return MockInterface
     */
    protected function getNewGearmanClientMock()
    {
        $client = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Client");
        $client->shouldReceive("setConfig");
        $client->shouldReceive("connect");

        return $client;
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
    protected function it_makes(Make $make, array $providers, array $config, $method, $interface)
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
    public function it_makes_workers()
    {
        $container = new Container();

        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Worker", function () {
            return $this->getNewGearmanWorkerMock();
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

        $this->it_makes(
            $make, $providers, $config, "worker",
            "Revolve\\Assistant\\Worker\\WorkerInterface"
        );
    }

    /**
     * @return MockInterface
     */
    protected function getNewGearmanWorkerMock()
    {
        $worker = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Worker");
        $worker->shouldReceive("setConfig");
        $worker->shouldReceive("connect");

        return $worker;
    }

    /**
     * @test
     */
    public function it_makes_tasks()
    {
        $container = new Container();

        $container->bind("Revolve\\Assistant\\Provider\\Gearman\\Task", function () {
            return $this->getNewGearmanTaskMock();
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

        $this->it_makes(
            $make, $providers, $config, "task",
            "Revolve\\Assistant\\Task\\TaskInterface"
        );
    }

    /**
     * @return MockInterface
     */
    protected function getNewGearmanTaskMock()
    {
        $task = $this->mock("Revolve\\Assistant\\Provider\\Gearman\\Task");
        $task->shouldReceive("setCallback");
        $task->shouldReceive("connect");

        return $task;
    }

    /**
     * @test
     */
    public function it_makes_messengers()
    {
        $container = new Container();

        $container->bind("Revolve\\Assistant\\Provider\\Memcached\\Messenger", function () {
            return $this->getNewMemcachedMessengerMock();
        });

        $container->bind("Revolve\\Assistant\\Provider\\Iron\\Messenger", function () {
            return $this->getNewIronMessengerMock();
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

        $this->it_makes(
            $make, $providers, $config, "messenger",
            "Revolve\\Assistant\\Messenger\\MessengerInterface"
        );
    }

    /**
     * @return MockInterface
     */
    protected function getNewMemcachedMessengerMock()
    {
        $memcached = $this->mock("Revolve\\Assistant\\Provider\\Memcached\\Messenger");
        $memcached->shouldReceive("setConfig");
        $memcached->shouldReceive("connect");

        return $memcached;
    }

    /**
     * @return MockInterface
     */
    protected function getNewIronMessengerMock()
    {
        $iron = $this->mock("Revolve\\Assistant\\Provider\\Iron\\Messenger");
        $iron->shouldReceive("setConfig");
        $iron->shouldReceive("connect");

        return $iron;
    }
}
