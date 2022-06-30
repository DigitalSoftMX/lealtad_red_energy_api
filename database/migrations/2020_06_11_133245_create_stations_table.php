<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('name');
            $table->String('abrev');
            $table->String('address');
            $table->String('phone');
            $table->String('email');
            $table->integer('total_timbres')->nullable();
            $table->integer('total_facturas')->nullable();
            $table->unsignedBigInteger('id_company');
            $table->String('number_station', 5);
            $table->integer('active')->nullable();
            $table->boolean('lealtad');
            $table->String('dns')->nullable();
            $table->timestamp('fail')->nullable();
            $table->String('ip')->nullable();
            $table->String('image');
            $table->timestamps();

            $table->foreign('id_company')->references('id')->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station');
    }
}
