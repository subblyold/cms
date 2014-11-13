<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreUidFieldsOnTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
        });

        Schema::table('product_images', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
        });

        Schema::table('product_options', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_options', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
        });

        Schema::table('product_images', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
        });

        Schema::table('product_images', function(Blueprint $table)
        {
            $table->dropColumn(array('uid'));
        });
    }

}
