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
            $table->bigInteger('seller_id')->unsigned();
            $table->enum('target', ['products', 'all', 'home']);
            $table->dropColumn('url');
            $table->dropTimestamps();
            $table->timestamp('start_at');
            $table->timestamp('end_at');

            $table->foreign('seller_id')->references('id')
                ->on('sellers')->onDelete('cascade');
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
