<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripAdditionalTaxTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_additional_tax_translations', function (Blueprint $table) {
            $table->increments('id');                     
            $table->integer('trip_id')->unsigned();    
            $table->biginteger('trip_additional_tax_id')->unsigned();      
            $table->string('name');                  
            $table->string('locale',5)->index();               
             $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_additional_tax_translations');
    }
}
