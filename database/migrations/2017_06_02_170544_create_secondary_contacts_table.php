<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecondaryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('information');
            $table->integer('contact_type_id')  ->unsigned();
            $table->integer('profile_id')       ->unsigned();
            $table->timestamps();

            $table->foreign('contact_type_id')  ->references('id')->on('contact_types');
            $table->foreign('profile_id')       ->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_contacts');
    }
}
