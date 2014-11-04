<?php

use Subbly\Subbly;
use Subbly\Tests\Support\TestCase;

class SubblyTest extends TestCase
{
    public function testVersion()
    {
        $this->assertEquals(Subbly::VERSION, '0.1.0-dev');
    }

    public function testApi()
    {
        $api = Subbly::api();
        $this->assertInstanceOf('Subbly\\Api\\Api', $api);

        $service = Subbly::api('subbly.user');
        $this->assertInstanceOf('Subbly\\Api\\Service\\Service', $service);

        $service = Subbly::api()->service('subbly.user');
        $this->assertInstanceOf('Subbly\\Api\\Service\\Service', $service);

        try {
            Subbly::api('subbly.a_very_wrong_service');

            $this->fail('Subbly\Api\Exception has not be raised.');
        }
        catch (\Subbly\Api\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testEvents()
    {
        // TODO
    }
}
