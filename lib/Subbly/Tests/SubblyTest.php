<?php

namespace Subbly\Tests;

use Subbly\Subbly;

class SubblyTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInstanceOf('Subbly\\Api\\Service', $service);

        $service = Subbly::api()->service('subbly.user');
        $this->assertInstanceOf('Subbly\\Api\\Service', $service);

        $service = Subbly::api()->service('subbly.wrongwrongwrong');
        $this->assertInstanceOf('Subbly\\Api\\Service', $service);
    }
}
