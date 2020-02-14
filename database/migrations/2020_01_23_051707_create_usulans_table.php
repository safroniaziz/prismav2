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
            $table->text('judul_penelitian');
            $table->string('skim_id');
            $table->string('bidang_id');
            $table->string('ketua_peneliti_nip');
            $table->string('ketua_peneliti_nama');
            $table->string('ketua_peneliti_prodi_id');
            $table->string('ketua_peneliti_prodi_nama');
            $table->string('ketua_peneliti_fakultas_id');
            $table->string('ketua_peneliti_fakultas_nama');
            $table->string('ketua_peneliti_nidn');
            $table->string('ketua_peneliti_jabatan_fungsional');
            $table->string('ketua_peneliti_jk');
            $table->string('ketua_peneliti_universitas');
            $table->text('abstrak');
            $table->string('kata_kunci');
            $table->string('peta_jalan')->nullable();
            $table->string('biaya_diusulkan');
            $table->string('tahun_usulan');
            $table->enum('status_usulan',['0','1','2','3'])->default('0');
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
