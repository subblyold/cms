<?php

namespace Subbly\Tests\Api\Service;

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\OrderService;
use Subbly\Core\Container;

class OrderServiceTest extends \PHPUnit_Framework_TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.order');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new OrderService($api);

        $this->assertNotNull($s);
    }

    public function testNewOrder()
    {
        $instance = $this->getService()->newOrder();
        $this->assertInstanceOf('Subbly\\Model\\Order', $instance);
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.order');
    }
}
