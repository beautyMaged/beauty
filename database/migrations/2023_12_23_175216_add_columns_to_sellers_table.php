<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellers', function (Blueprint $table) {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'FullManagerName',
                'ownerEmail',
                'ManagerEmail',
                'ManagerTel',
                'agreed',
                'allCategoriesCount',
                'bestSellingCat',
                'bestSellingProduct',
                'brandName',
                'categoriesCount',
                'categoriesNames',
                'compBranches',
                'compCustomerServiceEmail',
                'compCustomerServiceNum',
                'fieldOfInterest',
                'fillerTel',
                'fullFillerEmail',
                'fullFillerName',
                'q_data',
                'iban',
                'onlineTradeLicenes',
                'productsCount',
                'taxRecord',
                'tradeRecord',
                'storeLink',
                'storeLocation',
                'storeName',
                'subCategoriesCount',
                'taxNum',
                'tradeNumber',
                'validationNum',
            ]);
        });
    }
}
