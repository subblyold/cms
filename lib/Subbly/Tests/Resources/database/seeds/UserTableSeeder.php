<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;

use Subbly\Model\User;
use Subbly\Tests\Support\TestCase;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $faker = TestCase::faker();

        $user = User::create(array(
            'email'     => 'jon.snow@test.subbly.com',
            'password'  => 'hodor123',
            'firstname' => 'Jon',
            'lastname'  => 'Snow',
            'activated' => true,
        ));
        TestCase::addFixture('users.jon_snow', $user);

        // generate some others users
        for ($i=1; $i <= 10; $i++)
        {
            $user = User::create(array(
                'email'     => $faker->email,
                'password'  => $faker->password(),
                'firstname' => $faker->firstName,
                'lastname'  => $faker->lastName,
                'activated' => $i === 0 ? true : rand(0, 1),
            ));
            TestCase::addFixture('users.user_' . $i, $user);
        }
    }
}
