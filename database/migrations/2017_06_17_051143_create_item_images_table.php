<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('inventory_item_image', function (Blueprint $table) {
            $table->integer('inventory_id')  ->unsigned()        ->index();
            $table->foreign('inventory_id')  ->references('id')  ->on('inventories')->onDelete('cascade');

            $table->integer('item_image_id') ->unsigned()        ->index();
            $table->foreign('item_image_id') ->references('id')  ->on('item_images')->onDelete('cascade');
            
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
        Schema::dropIfExists('item_images');
        Schema::dropIfExists('inventory_item_image');
    }
}
