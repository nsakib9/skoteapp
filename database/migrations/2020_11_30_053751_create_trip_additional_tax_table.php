w<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripAdditionalTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_additional_tax', function (Blueprint $table) {
            $table->id();
            $table->string('tax_name',255);
            $table->decimal('tax_value', 11, 2);
            $table->integer('trip_id')->unsigned();           
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_additional_tax');
    }
}
