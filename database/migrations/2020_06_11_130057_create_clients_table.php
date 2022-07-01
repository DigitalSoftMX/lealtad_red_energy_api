<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('membership',15);
            $table->integer('points');
            $table->string('image')->nullable();
            $table->String('address')->nullable();
            $table->enum('active',['ACTIVE','LOCKED'])->default('ACTIVE');
            $table->unsignedBigInteger('user_id');
            $table->string('ids')->nullable();//enviar notificacion por cliente
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
