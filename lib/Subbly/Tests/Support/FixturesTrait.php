<?php

namespace Subbly\Tests\Support;

use Subbly\Model\ModelInterface;

trait FixturesTrait
{
    /** @var \ArrayObject $fixtures */
    private static $fixtures;

    /**
     * Add a new fixture
     *
     * @param string                        $name  The name of the fixture
     * @param \Subbly\Model\ModelInterface  $model The model to add in the fixtures
     */
    public static function addFixture($name, ModelInterface $model)
    {
        self::fixtures()->offsetSet($name, $model);
    }

    /**
     * Get a fixture by his name
     *
     * @param string  $name  The name of the fixture
     *
     * @return \Subbly\Model\ModelInterface
     */
    public static function getFixture($name)
    {
        return self::fixtures()->offsetGet($name);
    }

    /**
     * Get fixtures storage
     *
     * @return \ArrayObject
     */
    private static function fixtures()
    {
        if (!isset(self::$fixtures)) {
            self::$fixtures = new \ArrayObject;
        }

        return self::$fixtures;
    }
}
