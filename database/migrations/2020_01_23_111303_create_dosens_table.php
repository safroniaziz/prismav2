<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDosensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nm_lengkap');
            $table->string('nip');
            $table->string('nidn')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('golongan')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('jabatan_fungsional')->nullable();
            $table->string('prodi_kode')->nullable();
            $table->string('prodi')->nullable();
            $table->string('fakultas_kode')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('keahlian')->nullable();
            $table->string('perguruan_tinggi')->nullable();
            $table->string('alamat_institusi')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
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
            $table->string('golongan')->nullable();
            Schema::dropIfExists('dosens');
    }
}
