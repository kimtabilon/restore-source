<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemQuantityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_quantities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->text('remarks');
            $table->integer('inventory_id') ->unsigned();
            $table->timestamps();

            $table->foreign('inventory_id') ->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_quantities');
    }
}
