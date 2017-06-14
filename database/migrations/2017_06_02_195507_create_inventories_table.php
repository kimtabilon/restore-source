<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')          ->unsigned();
            $table->integer('donor_id')         ->unsigned();
            $table->integer('item_id')          ->unsigned();
            $table->integer('item_price_id')    ->unsigned();
            $table->integer('item_status_id')   ->unsigned();
            $table->integer('item_image_id')    ->unsigned();
            $table->integer('transaction_id')   ->unsigned();
            $table->timestamps();

            $table->foreign('user_id')          ->references('id')->on('users');
            $table->foreign('donor_id')         ->references('id')->on('donors');
            $table->foreign('item_id')          ->references('id')->on('items');
            $table->foreign('item_price_id')    ->references('id')->on('item_prices');
            $table->foreign('item_status_id')   ->references('id')->on('item_status');
            $table->foreign('item_image_id')    ->references('id')->on('item_images');
            $table->foreign('transaction_id')   ->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
