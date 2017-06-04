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
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email', 100)    ->unique();
            $table->integer('donor_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('donor_type_id')->references('id')->on('donor_types');
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
    }
}
