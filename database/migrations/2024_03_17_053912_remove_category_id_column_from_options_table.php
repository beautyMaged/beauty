<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCategoryIdColumnFromOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            
            $table->dropForeign(['category_id']);

            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {

            $table->unsignedBigInteger('category_id')->default(0);

            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
}
