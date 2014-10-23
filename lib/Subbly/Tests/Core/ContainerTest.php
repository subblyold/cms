<?php

namespace Subbly\Tests\Core;

use Subbly\Core\Container;

class ContainerTest extends \Subbly\Tests\Support\TestCase
{
    public function testConstruct()
    {
        $c = new Container();

        $this->assertNotNull($c);
    }
}
