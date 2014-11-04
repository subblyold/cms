<?php

namespace Subbly\Tests\Support;

use Illuminate\Support\Facades\Artisan;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use AssertionsTrait, FixturesTrait, ApplicationTrait;

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
