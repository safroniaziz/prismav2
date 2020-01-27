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
            $table->string('Perguruan Tinggi');
            $table->string('Program Study');
            $table->unsignedInteger('ketua_peneliti_id');
            $table->unsignedInteger('anggota1')->nullable();
            $table->unsignedInteger('anggota2')->nullable();
            $table->unsignedInteger('anggota3')->nullable();
            $table->unsignedInteger('anggota4')->nullable();
            $table->unsignedInteger('anggota5')->nullable();
            $table->text('abstrak');
            $table->string('kata_kunci');
            $table->string('peta_jalan');
            $table->string('biaya_diusulkan');
            $table->unsignedInteger('rancangan_anggaran_id');
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
