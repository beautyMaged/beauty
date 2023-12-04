<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->bigInteger('seller_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->enum('target', ['products', 'all', 'home']);
            $table->dropColumn('url');
            $table->dropColumn('main_title');
            $table->dropColumn('resource_id');
            $table->dropColumn('resource_type');
            $table->dropTimestamps();
            $table->timestamp('start_at');
            $table->timestamp('end_at');

            $table->foreign('seller_id')->references('id')
                ->on('sellers')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')
                ->on('categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
}
