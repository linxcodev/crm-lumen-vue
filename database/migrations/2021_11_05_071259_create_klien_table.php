<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('klien', function (Blueprint $table) {
            $table->id();
            
            $table->string('nama_lengkap');
            $table->string('phone');
            $table->string('email');
            $table->text('bagian');
            $table->text('budget');
            $table->text('lokasi');
            $table->text('zip');
            $table->text('kota');
            $table->text('negara');
            $table->boolean('is_active')->nullable()->default(1);
            
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('diedit_oleh')->nullable();
            $table->unsignedBigInteger('dihapus_oleh')->nullable();
            
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('dibuat_oleh')->references('id')->on('admin')->onDelete('cascade');
            $table->foreign('diedit_oleh')->references('id')->on('admin')->onDelete('cascade');
            $table->foreign('dihapus_oleh')->references('id')->on('admin')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('klien');
    }
}
