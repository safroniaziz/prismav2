<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaUsulansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_usulans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id');
            $table->string('anggota_nip');
            $table->string('anggota_nama');
            $table->string('anggota_prodi_id');
            $table->string('anggota_prodi_nama');
            $table->string('anggota_fakultas_id');
            $table->string('anggota_fakultas_nama');
            $table->string('ketua_peneliti_nidn');
            $table->string('anggota_jabatan_fungsional')->nullable();
            $table->string('anggota_jk')->nullable();
            $table->string('anggota_universitas');
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
        Schema::dropIfExists('anggota_usulans');
    }
}
