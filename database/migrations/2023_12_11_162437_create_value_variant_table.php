<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValueVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_variant', function (Blueprint $table) {
            $table->unsignedBigInteger('value_id');
            $table->unsignedBigInteger('variant_id');
            $table->primary(['value_id', 'variant_id']);

            // Foreign key constraints
            $table->foreign('value_id')->references('id')->on('values')->onDelete('restrict');
            $table->foreign('variant_id')->references('id')->on('variants')->onDelete('restrict');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('value_variant');
    }
}
