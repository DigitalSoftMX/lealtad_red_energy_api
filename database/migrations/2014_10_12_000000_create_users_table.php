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
            $table->string('name',50);
            $table->string('first_surname',50);
            $table->string('second_surname',50)->nullable();
            $table->string('email')->unique();
            $table->string('sex',10)->nullable();
            $table->string('phone',15)->nullable();
            $table->date('birthday')->nullable();
            $table->string('job')->nullable();
            $table->enum('active',['ACTIVE','LOCKED'])->default('ACTIVE');
            $table->string('external_id',100)->nullable();//login google
            $table->string('password',255)->nullable();
            $table->timestamp('email_verified_at')->nullable();

            // $table->rememberToken();
            $table->text('remember_token')->nullable();
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
