<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRancanganAnggaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rancangan_anggarans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('penelitian_id');
            $table->unsignedInteger('jenis_anggaran_id');
            $table->string('material');
            $table->string('justifikasi');
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
        Schema::dropIfExists('rancangan_anggarans');
    }
}
