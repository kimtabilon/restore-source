<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('given_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('username', 100) ->unique();
            $table->string('email', 100)    ->unique();
            $table->string('password');
            $table->integer('role_id')      ->unsigned();
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('role_id')      ->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
