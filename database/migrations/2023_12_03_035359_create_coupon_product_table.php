<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_product', function (Blueprint $table) {
            $table->bigInteger('coupon_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->enum('state', ['included', 'excluded']);

            $table->unique(['coupon_id', 'product_id']);
            $table->foreign('product_id')->references('id')
                ->on('products')->cascadeOnDelete();
            $table->foreign('coupon_id')->references('id')
                ->on('coupons')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_product');
    }
}
