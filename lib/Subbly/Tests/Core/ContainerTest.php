<?php

namespace Subbly\Tests\Core;

use Subbly\Core\Container;

class ContainerTest extends \Illuminate\Foundation\Testing\TestCase
{
    public function testConstruct()
    {
        $c = new Container();

        $this->assertNotNull($c);
    }
}
