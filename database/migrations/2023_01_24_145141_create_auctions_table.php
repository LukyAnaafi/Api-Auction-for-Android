<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id('id_auction');
            $table->date('date_open_auction');
            $table->date('date_close_auction');
            $table->bigInteger('final_price')->nullable();
            $table->unsignedBigInteger('id')->nullable();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_item');
            $table->unsignedBigInteger('id_staff');
            $table->foreign('id_item')->references('id_item')->on('items')->onDelete('cascade');
            $table->enum('status_item',['available','sold']);
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
        Schema::dropIfExists('auctions');
    }
};
