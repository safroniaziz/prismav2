<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviewers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nip');
            $table->string('nama');
            $table->string('prodi_id')->nullable();
            $table->string('prodi_nama')->nullable();
            $table->string('fakultas_id')->nullable();
            $table->string('fakultas_nama')->nullable();
            $table->string('nidn')->nullable();
            $table->string('jabatan_fungsional')->nullable();
            $table->string('jenis_kelamin');
            $table->string('universitas');
            $table->enum('jenis_reviewer',['internal','eksternal']);
            $table->string('password')->nullable();
            $table->enum('status',['1','0'])->default('1');
            $table->rememberToken();
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
        Schema::dropIfExists('reviewers');
    }
}
