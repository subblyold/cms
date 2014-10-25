<?php

namespace Subbly\Tests\Resources\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Subbly\Subbly;

class ProductTableSeeder extends Seeder {

    public function run()
    {
        DB::table('products')->delete();

        // Product::create(array(
        //     'name' => 'Awesome product',
        // ));
    }

}
