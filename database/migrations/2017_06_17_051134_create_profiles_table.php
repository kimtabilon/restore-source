<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 10)     ->nullable();
            $table->text('address')         ->nullable();
            $table->string('phone', 30)     ->nullable();
            $table->string('tel', 30)       ->nullable();
            $table->string('company')       ->nullable();
            $table->string('job_title', 100)->nullable();
            $table->string('catch_phrase')  ->nullable();
            $table->integer('donor_id')     ->unsigned()->index();
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('donors')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
