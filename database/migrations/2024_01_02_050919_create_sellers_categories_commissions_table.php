<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersCategoriesCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers_categories_commissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float("commission")->default(0);
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');

            // foreign key
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unique(['seller_id','category_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers_categories_commissions');
    }
}
