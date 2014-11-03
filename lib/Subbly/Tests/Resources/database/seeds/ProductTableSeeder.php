<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;

use Subbly\Model\Product;
use Subbly\Tests\Support\TestCase;

class ProductTableSeeder extends Seeder {

    public function run()
    {
        $faker = TestCase::faker();

        for ($i=1; $i <= 10; $i++)
        {
            $price      = $faker->randomFloat(2, 0, 99999999.99);
            $sale_price = round($price - ($price * (rand(5, 25) / 100)), 2);

            $product = Product::create(array(
                'sku'         => $faker->unique()->bothify('????????##'),
                'name'        => $faker->words(3, true),
                'description' => $faker->text(),
                'price'       => $price,
                'sale_price'  => $i === 0 ? null : $sale_price,
                'quantity'    => $faker->randomNumber(4),
            ));
            TestCase::addFixture('products.product_' . $i, $product);
        }
    }

}
