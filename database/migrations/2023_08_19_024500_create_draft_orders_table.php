<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_orders', function (Blueprint $table) {
            $table->id(); // Auto-incremental primary key
            $table->bigInteger('draft_order_id')->unique(); // Draft order ID
            $table->string('shop_name'); // Shop name
            $table->string('commission_id')->unique(); // Unique commission ID
            $table->decimal('commission_value', 10, 2); // Commission value with two decimal points
            $table->enum('commission_status', ['none', 'ready', 'completed']); // Commission status
            $table->timestamps(); // Created at and updated at timestamps
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draft_orders');
    }
}
