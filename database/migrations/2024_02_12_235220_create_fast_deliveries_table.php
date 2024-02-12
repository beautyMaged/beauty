<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFastDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fast_deliveries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('city_id');
            $table->float('cost')->default(0);
            $table->string('note')->nullable();

            // foreign keys
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fast_deliveries');
    }
}
