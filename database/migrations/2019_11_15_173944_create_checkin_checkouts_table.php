<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkin_checkouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('userId')->references('keyId')->on('users');
            $table->timestamp('arrival')->nullable();
            $table->timestamp('departure')->nullable();
            $table->timestamp('checkout')->nullable();
            $table->timestamp('checkin')->nullable();
            $table->integer('onBreakTimestamp')->nullable();
            $table->boolean('autoClosed')->nullable();
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
        Schema::dropIfExists('checkin_checkouts');
    }
}
