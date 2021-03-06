<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJenisAnggotaColumnToAnggotaUsulansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_usulans', function (Blueprint $table) {
            $table->enum('jenis_anggota',['internal','eksternal']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anggota_usulans', function (Blueprint $table) {
            $table->dropColumn('jenis_anggota');
        });
    }
}
