<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('market_price', 10);
            $table->timestamps();
        });

        Schema::create('inventory_item_price', function (Blueprint $table) {
            $table->integer('inventory_id')  ->unsigned()        ->index();
            $table->foreign('inventory_id')  ->references('id')  ->on('inventories')->onDelete('cascade');

            $table->integer('item_price_id') ->unsigned()        ->index();
            $table->foreign('item_price_id') ->references('id')  ->on('item_prices')->onDelete('cascade');
            
            $table->timestamps();
        });

        Schema::create('inventory_item_selling_price', function (Blueprint $table) {
            $table->integer('inventory_id')  ->unsigned()        ->index();
            $table->foreign('inventory_id')  ->references('id')  ->on('inventories')->onDelete('cascade');

            $table->integer('item_selling_price_id') ->unsigned()        ->index();
            $table->foreign('item_selling_price_id') ->references('id')  ->on('item_prices')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_prices');
        Schema::dropIfExists('inventory_item_price');
    }
}
