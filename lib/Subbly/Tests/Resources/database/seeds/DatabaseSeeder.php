<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Eloquent::unguard();

        $this->globalSeeds();

        if (App::environment('testing')) {
            $this->testingSeeds();
        }

        Eloquent::reguard();
    }

    public function globalSeeds()
    {
        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\UserRolesSeeder');
        $this->command->info('User roles seeded!');
    }

    public function testingSeeds()
    {
        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\SettingTableSeeder');
        $this->command->info('Setting table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\ProductTableSeeder');
        $this->command->info('Product table seeded!');
    }
}
