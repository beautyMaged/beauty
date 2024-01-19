<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingColumnsToCategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->boolean('top_rated')->default(false);
            $table->boolean('top_rated_globally')->default(false);
            $table->boolean('best_selling')->default(false);
            $table->boolean('best_selling_globally')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_product', function (Blueprint $table) {
            $table->dropColumn(['top_rated','top_rated_globally','best_selling','best_selling_globally']);
        });
    }
}