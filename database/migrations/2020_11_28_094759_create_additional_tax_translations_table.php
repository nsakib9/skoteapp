<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalTaxTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('additional_tax_translations', function (Blueprint $table) {
            $table->increments('id');     
            $table->integer('additional_tax_id')->unsigned();     
            $table->string('name');                  
            $table->string('locale',5)->index();     
            $table->unique(['additional_tax_id','locale']);     
             $table->foreign('additional_tax_id')->references('id')->on('additional_tax')->onDelete('cascade');      
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('additional_tax_translations');
    }
}
