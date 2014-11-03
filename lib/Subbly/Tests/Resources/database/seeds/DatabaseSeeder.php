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
        /**
         * Delete table content
         */
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = array('settings', 'users', 'products', 'orders');

        foreach ($tables as $table) {
            DB::table($table)->delete();
            $this->command->info(sprintf('"%s" table content deleted!', $table));
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /**
         * Call seeders
         */
        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\SettingTableSeeder');
        $this->command->info('Setting table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\ProductTableSeeder');
        $this->command->info('Product table seeded!');

        $this->call('Subbly\\Tests\\Resources\\database\\seeds\\OrderTableSeeder');
        $this->command->info('Order table seeded!');
    }
}
