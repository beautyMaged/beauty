<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('district_id');
            $table->double('longitude');
            $table->double('latitude');
            $table->string('street');
            $table->string('phone');
            $table->string('description')->nullable();

            // foreign keys
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
