<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_filters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('f_num');
            $table->bigInteger('s_num');
            $table->bigInteger('t_num');
            $table->bigInteger('fo_num');
            $table->string('bg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_filters');
    }
}
