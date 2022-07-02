<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            
            $table->string('nama');
            $table->unsignedBigInteger('id_perusahaan');
            $table->unsignedBigInteger('id_klien');
            $table->boolean('is_active')->nullable()->default(1);
            
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('diedit_oleh')->nullable();
            $table->unsignedBigInteger('dihapus_oleh')->nullable();
            
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            
            $table->foreign('id_perusahaan')->references('id')->on('perusahaan');
            $table->foreign('id_klien')->references('id')->on('klien');
            $table->foreign('dibuat_oleh')->references('id')->on('admin');
            $table->foreign('diedit_oleh')->references('id')->on('admin');
            $table->foreign('dihapus_oleh')->references('id')->on('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report');
    }
}
