<?php

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\ProductService;
use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class ProductServiceTest extends TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.product');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new ProductService($api);

        $this->assertNotNull($s);
    }

    public function testNewProduct()
    {
        $instance = $this->getService()->newProduct();
        $this->assertInstanceOf('Subbly\\Model\\Product', $instance);
    }

    /**
     * TODO test creation with valid and unvalid price format and check model validation
     */

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.product');
    }
}
