<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_term', function (Blueprint $table) {
            $table->id();

            $table->longText('body');
            $table->unsignedBigInteger('id_deal');

            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('id_deal')->references('id')->on('deal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_term');
    }
}
