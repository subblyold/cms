<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('name', 255);
            $table->integer('position');

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('parent_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function(Blueprint $table) {
            $table->dropForeign('product_categories_product_id_foreign');
        });
        Schema::table('product_categories', function(Blueprint $table) {
            $table->dropForeign('product_categories_product_categories_id_foreign');
        });

        Schema::drop('product_categories');
    }

}
