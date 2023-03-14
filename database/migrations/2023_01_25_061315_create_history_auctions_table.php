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
        Schema::create('history_auctions', function (Blueprint $table) {
            $table->id('id_history');
            $table->unsignedBigInteger('id_item');
            $table->foreign('id_item')->references('id_item')->on('items')->onDelete('cascade');
            $table->unsignedBigInteger('id_auction');
            $table->foreign('id_auction')->references('id_auction')->on('auctions')->onDelete('cascade');
            $table->unsignedBigInteger('id')->nullable();
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('bid_price');
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
        Schema::dropIfExists('history_auctions');
    }
};
