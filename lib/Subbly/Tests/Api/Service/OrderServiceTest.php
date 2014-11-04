<?php

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\OrderService;
use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class OrderServiceTest extends TestCase
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

    public function testAll()
    {
        $all = $this->getService()->all();

        $this->assertInstanceOf('Illuminate\\Database\\Eloquent\\Collection', $all);
        $this->assertCount(10, $all);
        $this->assertEquals(10, $all->count());
        $this->assertEquals(10, $all->total());

        $all = $this->getService()->all(array(
            'limit' => 1,
        ));
        $this->assertInstanceOf('Illuminate\\Database\\Eloquent\\Collection', $all);
        $this->assertCount(1, $all);
        $this->assertEquals(1, $all->count());
        $this->assertEquals(10, $all->total());
        $this->assertEquals(10, $all->total());

        $all = $this->getService()->all(array(
            'limit'  => 2,
            'offset' => 9,
        ));
        $this->assertInstanceOf('Illuminate\\Database\\Eloquent\\Collection', $all);
        $this->assertCount(1, $all);
        $this->assertEquals(1, $all->count());
        $this->assertEquals(10, $all->total());
    }

    public function testFind()
    {
        $fixture = TestCase::getFixture('orders.order_1');
        $id      = $fixture->id;
        $order   = $this->getService()->find($id);

        $this->assertInstanceOf('Subbly\\Model\\Order', $order);
        $this->assertEquals($fixture->id, $order->id);
        $this->assertEquals($fixture->user, $order->user);
        $this->assertEquals($fixture->total_price, $order->total_price);
        $this->assertEquals($fixture->status, $order->status);
    }

    public function testSearchBy()
    {
        // TODO
    }

    public function testCreate()
    {
        // TODO
    }

    public function testUpdate()
    {
        // TODO
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.order');
    }
}
