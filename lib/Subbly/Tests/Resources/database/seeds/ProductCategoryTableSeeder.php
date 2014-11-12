<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;

use Subbly\Model\ProductCategory;
use Subbly\Tests\Support\TestCase;

class ProductCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $faker = TestCase::faker();

        for ($i=1; $i <= 10; $i++)
        {
            $productCategory = ProductCategory::create(array(
                'product_id' => TestCase::getFixture('products.product_1')->id,
                'name'       => $faker->name,
            ));
            TestCase::addFixture('product_categories.product_category_' . $i, $productCategory);
        }
    }
}
