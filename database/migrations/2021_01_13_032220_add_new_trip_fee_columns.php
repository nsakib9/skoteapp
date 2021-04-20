<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewTripFeeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->text('surcharge_location');
            $table->decimal('black_car_fund',11,2)->nullable();
            $table->decimal('sales_tax',11,2)->nullable();
            $table->decimal('surcharge_amount', 11, 2)->nullable();
            $table->decimal('cancellation_fee',11,2)->default('0');
            $table->enum('is_paid',['Not Paid', 'Paid'])->default('Not Paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'surcharge_location',
                'black_car_fund',
                'sales_tax',
                'surcharge_amount',
                'cancellation_fee',
                'is_paid',
            ]);
        });
    }
}
