<?php

use Subbly\Model\Order;
use Subbly\Tests\Support\TestCase;

class OrdersControllerTest extends TestCase
{
    private $orderJSONFormat = array(
        'status' => 'string',

        '(user)'     => 'object',
        '(products)' => array('array', 'null'),
    );

    public function testIndex()
    {
        $response = $this->callJSON('GET', '/api/v1/orders');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('orders');
        $this->assertJSONTypes('orders[0]', $this->orderJSONFormat);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(Order::count(), $json->response->total);
    }

    public function testSearch()
    {
        /**
         * NOT OK
         */
        $response = $this->callJSON('GET', '/api/v1/orders/search');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        /**
         * OK
         */
        $searchQuery = TestCase::faker()->word;
        $response    = $this->callJSON('GET', '/api/v1/orders/search', array('q' => $searchQuery));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('orders');
        // $this->assertJSONTypes('orders[0]', $this->orderJSONFormat);
        $this->assertEquals($searchQuery, $json->response->query);
    }

    public function testShow()
    {
        // TODO test 404

        $order = TestCase::getFixture('orders.order_1');

        $response = $this->callJSON('GET', "/api/v1/orders/{$order->id}");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('order', $this->orderJSONFormat);
        $this->assertJSONEquals('order', $order);
    }

    // public function testStore()
    // {
    //     /**
    //      * NOT OK
    //      */
    //     // "order" not defined
    //     $response = $this->callJSON('POST', '/api/v1/orders');
    //
    //     $this->assertResponseStatus(400);
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertObjectHasAttribute('error', $json->response);
    //
    //     // "order" defined but empty
    //     $response = $this->callJSON('POST', '/api/v1/orders', array('order' => array()));
    //
    //     $this->assertResponseStatus(400);
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertObjectHasAttribute('error', $json->response);
    //
    //     /**
    //      * OK
    //      */
    //     $data = array(
    //         // TODO
    //     );
    //     $response = $this->callJSON('POST', '/api/v1/orders', array('order' => $data));
    //
    //     $this->assertResponseStatus(201);
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertJSONTypes('order', $this->orderJSONFormat);
    //     $this->assertJSONEquals('order', $data);
    // }
    //
    // public function testUpdate()
    // {
    //     $order = TestCase::getFixture('orders.order_8');
    //
    //     /**
    //      * NOT OK
    //      */
    //     // "order" not defined
    //     $response = $this->callJSON('PATCH', "/api/v1/orders/{$order->id}");
    //
    //     $this->assertResponseStatus(400);
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertObjectHasAttribute('error', $json->response);
    //
    //     /**
    //      * OK
    //      */
    //     // "order" defined but empty
    //     $response = $this->callJSON('PATCH', "/api/v1/orders/{$order->id}", array('order' => array()));
    //
    //     $this->assertResponseOk();
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertJSONTypes('order', $this->orderJSONFormat);
    //     $this->assertJSONEquals('order', $order);
    //
    //     // "orders" with datas
    //     $data = array(
    //         // TODO
    //     );
    //     $response = $this->callJSON('PATCH', "/api/v1/orders/{$order->id}", array('order' => $data));
    //
    //     $this->assertResponseOk();
    //     $this->assertResponseJSONValid();
    //
    //     $json = $this->getJSONContent();
    //     $this->assertJSONTypes('order', $this->orderJSONFormat);
    //     $this->assertJSONEquals('order', $data);
    // }
}
