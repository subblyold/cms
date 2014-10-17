<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddressesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_addresses', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('firstname', 255);
            $table->string('lastname', 255);
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();
            $table->string('zipcode', 10);
            $table->string('city', 255);
            $table->string('country', 255);

            $table->timestamps();
        });

        Schema::table('orders', function(Blueprint $table)
        {
            $table->integer('shipping_address_id')->unsigned();
            $table->foreign('shipping_address_id')->references('id')->on('order_addresses');

            $table->integer('billing_address_id')->unsigned();
            $table->foreign('billing_address_id')->references('id')->on('order_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->dropForeign('shipping_address_id');
            $table->dropForeign('billing_address_id');
        });

        Schema::drop('order_addresses');
    }

}
