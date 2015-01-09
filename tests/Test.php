<?php

namespace Revolve\Assistant\Test;

use Mockery;
use Mockery\Matcher\Type;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

abstract class Test extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @param string $class
     * @param array  $arguments
     *
     * @return MockInterface
     */
    protected function mock($class, array $arguments = [])
    {
        return Mockery::mock($class, $arguments);
    }

    /**
     * @param string $of
     *
     * @return Type
     */
    protected function type($of)
    {
        return Mockery::type($of);
    }
}
