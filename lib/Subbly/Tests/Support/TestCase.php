<?php

namespace Subbly\Tests\Support;

use Illuminate\Support\Facades\Artisan;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    private static $fixtures;
    private static $faker;

    protected $useDatabase = true;

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting     = true;
        $testEnvironment = 'testing';

        return require __DIR__.'/../../../../bootstrap/start.php';
    }

    /**
     *
     */
    public static function addFixture($name, $value)
    {
        self::fixtures()->offsetSet($name, $value);
    }

    /**
     *
     */
    public static function getFixture($name)
    {
        return self::fixtures()->offsetGet($name);
    }

    /**
     *
     */
    private static function fixtures()
    {
        if (!isset(self::$fixtures)) {
            self::$fixtures = new \ArrayObject;
        }

        return self::$fixtures;
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        if ($this->useDatabase) {
            $this->setUpDb();
        }
    }

    public function teardown()
    {
        // m::close();
    }

    public function setUpDb()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed', array('--class' => 'Subbly\Tests\Resources\database\seeds\DatabaseSeeder'));
    }

    public function teardownDb()
    {
        Artisan::call('migrate:reset');
    }
}
