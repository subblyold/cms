<?php

use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class ContainerTest extends TestCase
{
    public function testConstruct()
    {
        $c = new Container();

        $this->assertNotNull($c);
    }
}
