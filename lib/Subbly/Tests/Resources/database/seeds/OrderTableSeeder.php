<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;

use Subbly\Model\Order;
use Subbly\Tests\Support\TestCase;

class OrderTableSeeder extends Seeder {

    public function run()
    {
        $faker = TestCase::faker();

        for ($i=1; $i <= 10; $i++)
        {
            $order = Order::create(array(
                'user_id' => TestCase::getFixture('users.jon_snow')->id,
            ));
            TestCase::addFixture('orders.order_' . $i, $order);
        }
    }

}
