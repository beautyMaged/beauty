<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('sellers', function (Blueprint $table) {
        //     $table->dropColumn([
        //         'f_name',
        //         'l_name',
        //         'phone',
        //         'branch',
        //         'FullManagerName',
        //         'ownerEmail',
        //         'ManagerEmail',
        //         'ManagerTel',
        //         'agreed',
        //         'allCategoriesCount',
        //         'bestSellingCat',
        //         'bestSellingProduct',
        //         'brandName',
        //         'categoriesCount',
        //         'categoriesNames',
        //         'compBranches',
        //         'compCustomerServiceEmail',
        //         'compCustomerServiceNum',
        //         'fieldOfInterest',
        //         'fillerTel',
        //         'fullFillerEmail',
        //         'fullFillerName',
        //         'q_data',
        //         'iban',
        //         'onlineTradeLicenes',
        //         'productsCount',
        //         'taxRecord',
        //         'tradeRecord',
        //         'storeLink',
        //         'storeLocation',
        //         'storeName',
        //         'subCategoriesCount',
        //         'taxNum',
        //         'tradeNumber',
        //         'validationNum',
        //     ]);
        // });

        Schema::table('sellers', function (Blueprint $table){
        
            $table->string('trade_name');

            $table->string('e_trade_name');

            $table->enum('type',['company','organization', 'individual', 'local_seller', 'other']);

            $table->unsignedBigInteger('commercial_record')->nullable();

            $table->unsignedBigInteger('trade_gov_no')->nullable();

            $table->unsignedBigInteger('SBC_no')->nullable();

            $table->unsignedBigInteger('tax_no')->nullable();

            $table->unsignedBigInteger('city_id')->nullable();


            $table->unsignedBigInteger('agency_id')->nullable();

            $table->unsignedBigInteger('manufacturer_id')->nullable();


            // foreign key constraints

            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('set null');

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'trade_name',
                'e_trade_name',
                'type',
                'commercial_record',
                'trade_gov_no',
                'SBC_no',
                'tax_no',
                'agency_id',
                'manufacturer_id',
                'city_id'
            ]);
        });

        Schema::table('sellers', function (Blueprint $table) {
            $table->string('f_name',30)->nullable();
            $table->string('l_name',30)->nullable();
            $table->string('phone',25)->nullable();
            $table->string('branch')->nullable();
            $table->string('FullManagerName');
            $table->string('ownerEmail');
            $table->string('ManagerEmail');
            $table->string('ManagerTel');
            $table->boolean('agreed');
            $table->integer('allCategoriesCount');
            $table->string('bestSellingCat');
            $table->string('bestSellingProduct');
            $table->string('brandName');
            $table->integer('categoriesCount');
            $table->string('categoriesNames');
            $table->string('compBranches');
            $table->string('compCustomerServiceEmail');
            $table->integer('compCustomerServiceNum');
            $table->string('fieldOfInterest');
            $table->string('fillerTel');
            $table->string('fullFillerEmail');
            $table->string('fullFillerName');
            $table->json('q_data');
            $table->text('iban');
            $table->text('onlineTradeLicenes');
            $table->integer('productsCount');
            $table->text('taxRecord');
            $table->text('tradeRecord');
            $table->string('storeLink');
            $table->string('storeLocation');
            $table->string('storeName');
            $table->integer('subCategoriesCount');
            $table->integer('taxNum');
            $table->integer('tradeNumber');
            $table->integer('validationNum');
        });
    }
}
