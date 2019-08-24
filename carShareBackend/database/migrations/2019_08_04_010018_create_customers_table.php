<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            //$table->integer('credit_card_id')->unsigned();
            $table->string('address');
            $table->string('phone_number');
            $table->string('license_number')->unique();
            $table->boolean('status');
        });

        Schema::table('customers', function (Blueprint $table) {
            //$table->foreign('credit_card_id')->references('id')->on('credit_cards')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
