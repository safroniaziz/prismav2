<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsulansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usulans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul_penelitian');
            $table->string('bidang_penelitian');
            $table->unsignedInteger('ketua_peneliti_id');
            $table->text('abstrak');
            $table->string('kata_kunci');
            $table->string('peta_jalan');
            $table->string('biaya_diusulkan');
            $table->unsignedInteger('rancangan_anggaran_id');
            $table->string('tahun_usulan');
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
        Schema::dropIfExists('usulans');
    }
}
