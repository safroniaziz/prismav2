<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_alumnis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('usulan_id');
            $table->string('anggota_nama');
            $table->enum('jabatan',['laboran','teknisi','administrasi','alumni']);
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
        Schema::dropIfExists('anggota_alumnis');
    }
}
