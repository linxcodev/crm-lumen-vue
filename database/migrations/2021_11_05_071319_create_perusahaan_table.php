<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('nomor_pajak');
            $table->string('phone');
            $table->string('kota');
            $table->string('alamat_penagihan');
            $table->string('negara');
            $table->string('kode_pos', 64);
            $table->string('fax');
            $table->string('deskripsi');
            $table->boolean('is_active')->nullable()->default(1);
            
            $table->unsignedBigInteger('id_klien');
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('diedit_oleh')->nullable();
            $table->unsignedBigInteger('dihapus_oleh')->nullable();

            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
            
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
        Schema::dropIfExists('perusahaan');
    }
}
