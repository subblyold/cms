<?php

namespace Subbly\Tests\Resources\database\seeds;

use Sentry;

use Illuminate\Database\Seeder;

use Subbly\Model\User;
use Subbly\Tests\Support\TestCase;

class GroupsTableSeeder extends Seeder {

    public function run()
    {
        $groups = array(
            array(
                'name'        => 'Administrator',
                'permissions' => array(
                    'subbly.backend.auth'     => 1,
                    'subbly.backend.products' => 1,
                    'subbly.backend.settings' => 1,
                    'subbly.frontend'         => 1,
                ),
            ),
            array(
                'name'        => 'Customer',
                'permissions' => array(
                    'subbly.frontend' => 1,
                ),
            ),
        );

        foreach ($groups as $group)
        {
            try
            {
                $group = Sentry::createGroup($group);
                TestCase::addFixture('groups.group_' . snake_case($group['name']), $group);
            }
            catch (\Exception $e) {}
        }
    }
}
