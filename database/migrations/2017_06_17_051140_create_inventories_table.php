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
            $table->integer('user_id')          ->unsigned()->index();
            $table->integer('donor_id')         ->unsigned()->index();
            $table->integer('item_id')          ->unsigned()->index();
            $table->integer('item_status_id')   ->unsigned()->index();
            $table->integer('transaction_id')   ->unsigned()->index();
            $table->integer('quantity');
            $table->text('remarks');
            $table->timestamps();

            $table->foreign('user_id')          ->references('id')->on('users')         ->onDelete('cascade');
            $table->foreign('donor_id')         ->references('id')->on('donors')        ->onDelete('cascade');
            $table->foreign('item_id')          ->references('id')->on('items')         ->onDelete('cascade');
            $table->foreign('item_status_id')   ->references('id')->on('item_status')   ->onDelete('cascade');
            $table->foreign('transaction_id')   ->references('id')->on('transactions')  ->onDelete('cascade');
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
