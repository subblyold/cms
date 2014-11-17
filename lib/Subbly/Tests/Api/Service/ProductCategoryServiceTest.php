<?php

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\ProductCategoryService;
use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class ProductCategoryServiceTest extends TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.product_category');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new ProductCategoryService($api);

        $this->assertNotNull($s);
    }

    public function testNewProductCategory()
    {
        $instance = $this->getService()->newProductCategory();
        $this->assertInstanceOf('Subbly\\Model\\ProductCategory', $instance);
    }

    public function testFind()
    {
        $fixture = TestCase::getFixture('product_categories.product_category_4');
        $uid     = $fixture->uid;
        $productCategory = $this->getService()->find($uid);

        $this->assertInstanceOf('Subbly\\Model\\ProductCategory', $productCategory);
        $this->assertEquals($fixture->name, $productCategory->name);
    }

    public function testFindByProduct()
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

    public function testDelete()
    {
        // TODO
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.product_category');
    }
}
