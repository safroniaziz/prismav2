<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_mahasiswas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('usulan_id');
            $table->string('anggota_npm');
            $table->string('anggota_nama');
            $table->enum('anggota_jk',['L','P']);
            $table->string('anggota_prodi_nama');
            $table->string('anggota_fakultas_nama');
            $table->string('anggota_angkatan');
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
        Schema::dropIfExists('anggota_mahasiswas');
    }
}
