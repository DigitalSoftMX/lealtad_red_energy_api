<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesQrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesqrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('tiket_id');
            // $table->unsignedBigInteger('dispatcher_id');
            $table->unsignedBigInteger('product_id');
            $table->double('cant');
            $table->double('points');
            $table->double('payment');
            // $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('client_id');
            // $table->unsignedBigInteger('time_id');
            // $table->integer('no_bomb');
            $table->unsignedBigInteger('transmitter_id')->nullable();
            $table->timestamps();

            // $table->foreign('dispatcher_id')->references('id')->on('dispatchers')
            //     ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');

            // $table->foreign('schedule_id')->references('id')->on('schedules')
            //     ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('station_id')->references('id')->on('stations')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('client_id')->references('id')->on('clients')
                ->onDelete('cascade')->onUpdate('cascade');

            // $table->foreign('time_id')->references('id')->on('register_times')
            //     ->onDelete('cascade')
            //     ->onUpdate('cascade');

            $table->foreign('transmitter_id')->references('id')->on('clients')
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
        Schema::dropIfExists('sales');
    }
}
