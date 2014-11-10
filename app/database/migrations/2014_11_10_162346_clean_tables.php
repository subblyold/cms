<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
            $table->string('phone_work')->nullable();
            $table->string('phone_home')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->text('other_informations')->nullable();

            DB::statement('ALTER TABLE `user_addresses` MODIFY `address2` VARCHAR(255) NULL;');
        });

        Schema::table('order_addresses', function(Blueprint $table)
        {
            $table->string('uid', 32)->unique();
            $table->string('phone_work')->nullable();
            $table->string('phone_home')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->text('other_informations')->nullable();

            DB::statement('ALTER TABLE `user_addresses` MODIFY `address2` VARCHAR(255) NULL;');
        });

        Schema::table('products', function(Blueprint $table)
        {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_addresses', function(Blueprint $table)
        {
            DB::statement('ALTER TABLE `order_addresses` MODIFY `address2` VARCHAR(255) NOT NULL;');

            $table->dropColumn(array('uid', 'phone_work', 'phone_home', 'phone_mobile', 'others_informations'));
        });

        Schema::table('user_addresses', function(Blueprint $table)
        {
            DB::statement('ALTER TABLE `user_addresses` MODIFY `address2` VARCHAR(255) NOT NULL;');

            $table->dropColumn(array('uid', 'phone_work', 'phone_home', 'phone_mobile', 'others_informations'));
        });
    }

}
