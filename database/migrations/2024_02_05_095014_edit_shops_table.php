<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table){
        
            $table->string('trade_name');

            $table->string('e_trade_name');

            $table->enum('type',['company','organization', 'individual', 'local_seller', 'other']);

            $table->unsignedBigInteger('commercial_record')->nullable();

            $table->unsignedBigInteger('trade_gov_no')->nullable();

            $table->unsignedBigInteger('AUTH_no')->nullable();

            $table->enum('auth_authority',['maroof','SBC'])->nullable();

            $table->unsignedBigInteger('tax_no')->nullable();

            $table->unsignedBigInteger('city_id')->nullable();

            $table->unsignedBigInteger('country_id')->nullable();


            $table->unsignedBigInteger('agency_id')->nullable();

            $table->unsignedBigInteger('manufacturer_id')->nullable();


            // foreign key constraints

            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('set null');

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            // drop foreign keys
            $table->dropForeign(['agency_id']);
            $table->dropForeign(['manufacturer_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['country_id']);

            $table->dropColumn([
                'trade_name',
                'e_trade_name',
                'type',
                'commercial_record',
                'trade_gov_no',
                'AUTH_no',
                'tax_no',
                'agency_id',
                'manufacturer_id',
                'city_id',
                'country_id',
                'auth_authority'
            ]);
        });

        
    }
}
