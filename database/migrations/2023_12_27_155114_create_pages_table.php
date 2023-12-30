<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('list_order');
            $table->string('title');
            $table->string('description');
            $table->unsignedBigInteger('page_category_id');
            $table->unique(['page_category_id','list_order']);

            // foreign key
            $table->foreign('page_category_id')->references('id')->on('page_categories')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
