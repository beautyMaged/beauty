<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeBannerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_banner_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title_o');
            $table->string('title_t');
            $table->string('description_o');
            $table->string('description_t');
            $table->string('image_o');
            $table->string('image_t');
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
        Schema::dropIfExists('home_banner_settings');
    }
}
