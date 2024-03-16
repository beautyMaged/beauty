<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSellerPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('seller_policies', 'refund_policies');
        Schema::table('refund_policies', function (Blueprint $table) {
            $table->id();
            $table->dropColumn(['shipping_min', 'shipping_max']);
            $table->integer('days_to_refund_before_reception');
            $table->integer('min_days_to_refund');
            $table->integer('max_days_to_refund');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('refund_policies', 'seller_policies');
        Schema::table('seller_policies', function (Blueprint $table) {
            $table->integer('shipping_min');
            $table->integer('shipping_max');
            $table->dropColumn(['id', 'days_to_refund_before_reception', 'min_days_to_refund', 'max_days_to_refund']);
        });
    }
}
