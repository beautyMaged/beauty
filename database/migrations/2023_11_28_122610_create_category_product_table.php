<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();

            $table->unique(['category_id', 'product_id']);
            $table->foreign('category_id')->references('id')
                ->on('categories')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')
                ->on('products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');
    }
}
