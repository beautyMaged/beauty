<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicySellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_seller', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('note')->nullable();
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('policy_id');

            // foreign keys
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
            $table->unique(['seller_id', 'policy_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_seller');
    }
}
