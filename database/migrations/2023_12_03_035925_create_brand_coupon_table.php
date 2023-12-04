<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_coupon', function (Blueprint $table) {
            $table->bigInteger('coupon_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->enum('state', ['included', 'excluded']);

            $table->unique(['coupon_id', 'brand_id']);
            $table->foreign('brand_id')->references('id')
                ->on('brands')->cascadeOnDelete();
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
        Schema::dropIfExists('brand_coupon');
    }
}
