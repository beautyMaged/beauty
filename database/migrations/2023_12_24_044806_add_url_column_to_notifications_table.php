<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlColumnToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('url')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();

            // foreign keys
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('sellers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('url');

            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');

            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
}
