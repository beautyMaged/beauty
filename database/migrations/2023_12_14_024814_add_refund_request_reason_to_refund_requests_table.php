<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundRequestReasonToRefundRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->enum("refund_request_reason", ["different", "expired", "dislike", "not_ordered", "other"])->default("different");
            $table->string("bill_image");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn('refund_request_reason');
            $table->dropColumn('bill_image');
        });
    }
}
