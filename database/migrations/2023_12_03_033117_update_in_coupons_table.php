<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('added_by');
            $table->dropColumn('coupon_type');
            $table->dropColumn('coupon_bearer');
            $table->dropColumn('customer_id');
            $table->dropColumn('start_date');
            $table->dropColumn('expire_date');
            $table->dropColumn('limit');
            $table->set('payment_methods', [
                "credit_card",
                "paypal",
                "mada",
                "bank_transfer",
                "apple_pay",
                "bank_transfer",
            ]);
            $table->enum('exclude_discounted', ['true', 'false']);
            $table->enum('free_delivery', ['true', 'false']);
            $table->bigInteger('seller_id')->unsigned()->nullable()->change();
            $table->decimal('max_discount', 9, 3, true)->change();
            $table->decimal('min_purchase', 9, 3, true)->change();
            $table->decimal('discount', 9, 3, true)->change();
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->integer('limit_once');
            $table->integer('limit_all');

            $table->unique('code');
            $table->foreign('seller_id')->references('id')
                ->on('sellers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
}
