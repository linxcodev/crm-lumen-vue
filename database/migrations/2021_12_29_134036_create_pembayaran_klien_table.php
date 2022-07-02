<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranKlienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_klien', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_klien');
            $table->string('invoice', 100);
            $table->string('tipe_pembayaran', 100);
            $table->integer('nominal');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamps();

            $table->foreign('id_klien')->references('id')->on('klien')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_klien');
    }
}
