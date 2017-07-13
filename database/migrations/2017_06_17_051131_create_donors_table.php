<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('given_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email', 100)    ->unique();
            $table->integer('donor_type_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('donor_type_id')->references('id')->on('donor_types')->onDelete('cascade');
        });

        Schema::create('donor_inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inventory_id') ->unsigned()->index();
            $table->integer('donor_id')     ->unsigned()->index();
            $table->timestamps();

            $table->foreign('inventory_id') ->references('id')->on('inventories')   ->onDelete('cascade');
            $table->foreign('donor_id')     ->references('id')->on('donors')        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donors');
        Schema::dropIfExists('donor_inventory');
    }
}
