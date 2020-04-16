<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalUsulansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_usulans', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->enum('status',['0','1'])->default('0')->nullable();
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
        Schema::dropIfExists('jadwal_usulans');
    }
}
