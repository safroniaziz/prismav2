<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLuaranKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luaran_kegiatans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id');
            $table->string('judul_luaran');
            $table->string('jenis_kegiatan');
            $table->string('jenis_publikasi');
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
        Schema::dropIfExists('luaran_kegiatans');
    }
}
