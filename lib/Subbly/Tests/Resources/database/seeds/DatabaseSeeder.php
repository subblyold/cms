<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Eloquent::unguard();

        if (App::environment('testing')) {
            $this->testingDeleteTables();
        }

        $this->globalSeeds();

        if (App::environment('testing')) {
            $this->testingSeeds();
        }

        Eloquent::reguard();
    }

    private function globalSeeds()
    {
        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\GroupsTableSeeder');
        $this->command->info('Group table seeded!');
    }

    private function testingDeleteTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = array('settings', 'users', 'user_addresses', 'products', 'orders', 'users_groups', 'groups');

        foreach ($tables as $table) {
            DB::table($table)->delete();
            $this->command->info(sprintf('"%s" table content deleted!', $table));
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function testingSeeds()
    {
        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\SettingTableSeeder');
        $this->command->info('Setting table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\UserAddressTableSeeder');
        $this->command->info('UserAddress table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\ProductTableSeeder');
        $this->command->info('Product table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\OrderTableSeeder');
        $this->command->info('Order table seeded!');
    }
}
