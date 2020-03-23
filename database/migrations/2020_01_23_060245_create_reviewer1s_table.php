<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewer1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviewer1s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id');
            $table->string('reviewer_nip');
            $table->string('reviewer_nama');
            $table->string('reviewer_prodi_id');
            $table->string('reviewer_prodi_nama');
            $table->string('reviewer_fakultas_id');
            $table->string('reviewer_fakultas_nama');
            $table->string('ketua_peneliti_nidn');
            $table->string('reviewer_jabatan_fungsional')->nullable();
            $table->string('reviewer_jk')->nullable();
            $table->string('reviewer_universitas');
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
        Schema::dropIfExists('reviewer1s');
    }
}
