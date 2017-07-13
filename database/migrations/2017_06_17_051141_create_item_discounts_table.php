<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('percent', 10);
            $table->string('type', 100);
            $table->text('remarks')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('inventory_item_discount', function (Blueprint $table) {
            $table->integer('inventory_id')     ->unsigned()        ->index();
            $table->foreign('inventory_id')     ->references('id')  ->on('inventories')->onDelete('cascade');

            $table->integer('item_discount_id') ->unsigned()        ->index();
            $table->foreign('item_discount_id') ->references('id')  ->on('item_discounts')->onDelete('cascade');
            
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
        Schema::dropIfExists('item_discounts');
        Schema::dropIfExists('inventory_item_discount');
    }
}
