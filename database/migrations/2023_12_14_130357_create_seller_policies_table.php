<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_policies', function (Blueprint $table) {
            $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
            $table->integer('shipping_min');
            $table->integer('shipping_max');
            $table->integer('refund_max');
            $table->integer('substitution_max');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_policies');
    }
}
