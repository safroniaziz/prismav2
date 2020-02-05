<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranHonorOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggaran_honor_outputs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rancangan_anggaran_id');
            $table->string('keterangan_honor');
            $table->string('biaya');
            $table->string('hari_per_minggu');
            $table->string('jumlah_minggu');
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
        Schema::dropIfExists('anggaran_honor_outputs');
    }
}
