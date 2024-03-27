<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateProductNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_names', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });
        // drop name column from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('name_id')->nullable();
            // Define foreign key 
            $table->foreign('name_id')->references('id')->on('product_names');
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
            $table->dropForeign(['name_id']);
            $table->dropColumn('name_id');
            $table->string('name')->default('product_names');
        });
        Schema::dropIfExists('product_names');
    }
}
