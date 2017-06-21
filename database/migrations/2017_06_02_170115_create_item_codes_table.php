<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('item_id')          ->unsigned()->index();
            $table->integer('item_code_type_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('item_id')          ->references('id')->on('items')->onDelete('cascade');
            $table->foreign('item_code_type_id')->references('id')->on('item_code_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_codes');
    }
}
