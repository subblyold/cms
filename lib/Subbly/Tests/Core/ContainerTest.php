<?php

namespace Subbly\Tests\Core;

use Subbly\Core\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $c = new Container();

        $this->assertNotNull($c);
    }
}
