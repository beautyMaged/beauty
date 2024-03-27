    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class RenameSalePriceColumnFromVariantsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('variants', function (Blueprint $table) {
                $table->renameColumn('sale_price', 'discount');

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('variants', function (Blueprint $table) {
                $table->renameColumn('discount', 'sale_price');
            });
        }
    }
