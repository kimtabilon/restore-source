<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('percent', 10);
            $table->text('remarks');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('user_id')      ->unsigned();
            $table->integer('inventory_id') ->unsigned();
            $table->timestamps();

            $table->foreign('user_id')      ->references('id')->on('users');
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
        Schema::dropIfExists('discounts');
    }
}
