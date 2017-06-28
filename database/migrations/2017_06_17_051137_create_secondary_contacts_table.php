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
            $table->integer('contact_type_id')  ->unsigned()->index();
            $table->integer('profile_id')       ->unsigned()->index();
            $table->timestamps();

            $table->foreign('contact_type_id')  ->references('id')->on('contact_types')->onDelete('cascade');
            $table->foreign('profile_id')       ->references('id')->on('profiles')->onDelete('cascade');
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
