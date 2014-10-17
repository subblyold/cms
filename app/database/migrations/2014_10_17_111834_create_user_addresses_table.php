<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddressesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 255);
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->string('address1', 255);
            $table->string('address2', 255);
            $table->string('zipcode', 10);
            $table->string('city', 255);
            $table->string('country', 255);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropForeign('user_addresses_user_id_foreign');
        });

        Schema::drop('user_addresses');
    }

}
