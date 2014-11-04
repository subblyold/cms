<?php

use Subbly\Model\Product;
use Subbly\Tests\Support\TestCase;

class ProductsControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->call('GET', '/api/v1/products');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('products', $json->response);
        $this->assertJSONCollectionResponse($json->response);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(Product::count(), $json->response->total);
    }

    public function testSearch()
    {
        /**
         * NOT OK
         */
        $response = $this->call('GET', '/api/v1/products/search');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        /**
         * OK
         */
        $searchQuery = TestCase::faker()->word;
        $response    = $this->call('GET', '/api/v1/products/search', array('q' => $searchQuery));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('products', $json->response);
        $this->assertJSONCollectionResponse($json->response);
        $this->assertEquals($searchQuery, $json->response->query);
    }

    public function testShow()
    {
        $product = TestCase::getFixture('products.product_1');

        $response = $this->call('GET', "/api/v1/products/{$product->sku}");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('product', $json->response);
        $this->assertEquals($product->sku, $json->response->product->sku);
        $this->assertEquals($product->name, $json->response->product->name);
    }

    public function testStore()
    {
        /**
         * NOT OK
         */
        // "product" not defined
        $response = $this->call('POST', '/api/v1/products');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "product" defined but empty
        $response = $this->call('POST', '/api/v1/products', array('product' => array()));

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        $data = array(
            'sku'         => TestCase::faker()->unique()->bothify('????????##'),
            'name'        => TestCase::faker()->words(3, true),
            'description' => TestCase::faker()->text(),
            'price'       => TestCase::faker()->randomFloat(2, 0, 99999999.99),
            // 'sale_price'  => null,
            'quantity'    => TestCase::faker()->randomNumber(4),
        );
        $response = $this->call('POST', '/api/v1/products', array('product' => $data));

        $this->assertResponseStatus(201);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('product', $json->response);
        $this->assertEquals($data['sku'], $json->response->product->sku);
        $this->assertEquals($data['name'], $json->response->product->name);
        $this->assertEquals($data['description'], $json->response->product->description);
        $this->assertEquals($data['price'], $json->response->product->price);
        // $this->assertEquals($data['sale_price'], $json->response->product->sale_price);
        $this->assertEquals($data['quantity'], $json->response->product->quantity);
    }

    public function testUpdate()
    {
        $product = TestCase::getFixture('products.product_8');

        /**
         * NOT OK
         */
        // "product" not defined
        $response = $this->call('PATCH', "/api/v1/products/{$product->sku}");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        // "product" defined but empty
        $response = $this->call('PATCH', "/api/v1/products/{$product->sku}", array('product' => array()));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('product', $json->response);
        $this->assertEquals($product->sku, $json->response->product->sku);
        // TODO add others fields

        // "products" with datas
        $data = array(
            'name' => TestCase::faker()->word,
        );
        $response = $this->call('PATCH', "/api/v1/products/{$product->sku}", array('product' => $data));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('product', $json->response);
        $this->assertEquals($product->sku, $json->response->product->sku);
        // TODO add others fields
    }
}
