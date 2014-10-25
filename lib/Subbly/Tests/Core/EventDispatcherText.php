<?php

namespace Subbly\Tests\Core;

use Subbly\Core\Container;

class EventDispatcherTest extends \Subbly\Tests\Support\TestCase
{
    public function testConstruct()
    {
        $ed = new EventDispatcher();

        $this->assertNotNull($ed);
    }
}
