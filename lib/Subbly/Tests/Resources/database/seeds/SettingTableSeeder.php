<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Subbly\Subbly;

class SettingTableSeeder extends Seeder {

    public function run()
    {
        Subbly::api('subbly.setting')->all();
    }

}
