<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;

use Subbly\Model\UserAddress;
use Subbly\Tests\Support\TestCase;

class UserAddressTableSeeder extends Seeder
{
    public function run()
    {
        $faker = TestCase::faker();

        for ($i=1; $i <= 10; $i++)
        {
            $userAddress = UserAddress::create(array(
                'user_id'   => TestCase::getFixture('users.jon_snow')->id,
                'name'      => $faker->name,
                'firstname' => $faker->firstName,
                'lastname'  => $faker->lastName,
                'address1'  => $faker->streetAddress,
                'zipcode'   => $faker->postcode,
                'city'      => $faker->city,
                'country'   => $faker->country,

                'phone_work'   => rand(0, 1) === 0 ? null : $faker->phoneNumber,
                'phone_home'   => rand(0, 1) === 0 ? null : $faker->phoneNumber,
                'phone_mobile' => rand(0, 1) === 0 ? null : $faker->phoneNumber,
            ));
            TestCase::addFixture('user_addresses.user_address_' . $i, $userAddress);
        }
    }
}
