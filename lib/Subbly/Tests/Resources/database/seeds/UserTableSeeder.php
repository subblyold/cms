<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Subbly\Model\User;
use Subbly\Tests\Support\TestCase;

class UserTableSeeder extends Seeder {

    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('users')->delete();

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
            User::create(array(
                'email'     => $faker->email,
                'password'  => $faker->text(45),
                'firstname' => $faker->firstName,
                'lastname'  => $faker->lastName,
                'activated' => $i === 0 ? true : rand(0, 1),
            ));
            TestCase::addFixture('users.user_' . $i, $user);
        }
    }

}
