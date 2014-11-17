<?php

use Subbly\Model\Product;
use Subbly\Tests\Support\TestCase;

class ProductsControllerTest extends TestCase
{
    private $productJSONFormat = array(
        'sku'         => 'string',
        'name'        => 'string',
        'description' => 'string',
        'price'       => 'double',
        'sale_price'  => array('double', 'null'),
        'quantity'    => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',

        '(images)'     => 'array',
        '(options)'    => 'array',
        '(categories)' => 'array',
    );

    public function testIndex()
    {
        $response = $this->callJSON('GET', '/api/v1/products');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('products');
        $this->assertJSONTypes('products[0]', $this->productJSONFormat);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(Product::count(), $json->response->total);
    }

    public function testSearch()
    {
        /**
         * NOT OK
         */
        $response = $this->callJSON('GET', '/api/v1/products/search');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        /**
         * OK
         */
        $searchQuery = TestCase::faker()->word;
        $response    = $this->callJSON('GET', '/api/v1/products/search', array('q' => $searchQuery));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('products');
        // $this->assertJSONTypes('products[0]', $this->productJSONFormat);
        $this->assertEquals($searchQuery, $json->response->query);
    }

    public function testShow()
    {
        // TODO test 404

        $product = TestCase::getFixture('products.product_1');

        $response = $this->callJSON('GET', "/api/v1/products/{$product->sku}");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('product', $this->productJSONFormat);
        $this->assertJSONEquals('product', $product);
    }

    public function testStore()
    {
        $faker = TestCase::faker();
        /**
         * NOT OK
         */
        // "product" not defined
        $response = $this->callJSON('POST', '/api/v1/products');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "product" defined but empty
        $response = $this->callJSON('POST', '/api/v1/products', array('product' => array()));

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        $data = array(
            'sku'         => $faker->unique()->bothify('????????##'),
            'name'        => $faker->words(3, true),
            'description' => $faker->text(),
            'price'       => round($faker->randomFloat(2, 0, 99999999.99), 2),
            // 'sale_price'  => null,
            'quantity'    => $faker->randomNumber(4),
        );
        $response = $this->callJSON('POST', '/api/v1/products', array('product' => $data));

        $this->assertResponseStatus(201);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('product', $this->productJSONFormat);
        $this->assertJSONEquals('product', $data);
    }

    public function testUpdate()
    {
        $faker   = TestCase::faker();
        $product = TestCase::getFixture('products.product_8');

        /**
         * NOT OK
         */
        // "product" not defined
        $response = $this->callJSON('PATCH', "/api/v1/products/{$product->sku}");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        // "product" defined but empty
        $response = $this->callJSON('PATCH', "/api/v1/products/{$product->sku}", array('product' => array()));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('product', $this->productJSONFormat);
        $this->assertJSONEquals('product', $product);

        // "products" with datas
        $data = array(
            'name' => $faker->word,
        );
        $response = $this->callJSON('PATCH', "/api/v1/products/{$product->sku}", array('product' => $data));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('product', $this->productJSONFormat);
        $this->assertJSONEquals('product', $data);
    }
}
