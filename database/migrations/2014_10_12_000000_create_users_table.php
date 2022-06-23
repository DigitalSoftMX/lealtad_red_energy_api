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
            $table->bigIncrements('id');
            $table->String('name',50);
            $table->String('first_surname',50);
            $table->String('second_surname',50)->nullable();
            $table->String('username',15)->unique();//menbresia
            $table->String('email')->unique();
            $table->String('sex',10)->nullable();
            $table->String('phone',15)->nullable();
            $table->String('address',255)->nullable();
            $table->integer('active')->default(1);
            $table->String('password',255)->nullable();
            $table->String('external_id',100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            // $table->text('remember_token')->nullable();
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
        Schema::dropIfExists('users');
    }
}
