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
        Schema::create('protal_user', function (Blueprint $table) {
             $table->increments('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('password');
            $table->integer('role_id');
            $table->integer('client_id');
            $table->integer('supplier_id');
            $table->boolean('active');
            $table->boolean('reset_in_progress');
            $table->dateTime('last_login');
            $table->rememberToken();
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
        Schema::dropIfExists('protal_user');
    }
}
