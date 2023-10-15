<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('category_ids')->change();
            $table->json('colors')->change();
            $table->json('variation')->change();
            $table->json('choice_options')->change();
            $table->json('attributes')->change();
            $table->json('color_image')->change();
            $table->json('images')->change();
            $table->json('choice_options')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
