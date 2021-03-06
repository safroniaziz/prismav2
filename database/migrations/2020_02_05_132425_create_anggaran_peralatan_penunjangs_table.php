<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranPeralatanPenunjangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_peralatan_penunjangs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rancangan_anggaran_id');
            $table->string('material');
            $table->string('justifikasi_pembelian');
            $table->string('kuantitas');
            $table->string('harga_satuan');
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
        Schema::dropIfExists('anggaran_peralatan_penunjangs');
    }
}
