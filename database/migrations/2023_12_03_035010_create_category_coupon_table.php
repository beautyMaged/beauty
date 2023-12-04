<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_coupon', function (Blueprint $table) {
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('coupon_id')->unsigned();
            $table->enum('state', ['included', 'excluded']);

            $table->unique(['category_id', 'coupon_id']);
            $table->foreign('coupon_id')->references('id')
                ->on('coupons')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')
                ->on('categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_coupon');
    }
}
