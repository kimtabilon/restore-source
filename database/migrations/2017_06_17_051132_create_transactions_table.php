<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('da_number');
            $table->string('remarks');
            $table->integer('payment_type_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');
        });

        Schema::create('inventory_transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id')     ->unsigned()->index();
            $table->integer('transaction_id')   ->unsigned()->index();
            $table->timestamps();

            $table->foreign('inventory_id')     ->references('id')->on('inventories')   ->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('inventory_transaction');
    }
}
