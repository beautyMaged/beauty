<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {

            $table->unsignedBigInteger('product_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        
            $table->boolean('is_default')->default(false); //the default variant of the product
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variants', function (Blueprint $table) {

            $table->dropForeign(['product_id']);

            $table->dropColumn(['product_id', 'price', 'is_default']);
        });
    }
}
