<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiFormulir2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_formulir2s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id');
            $table->unsignedInteger('formulir_id');
            $table->string('reviewer_id');
            $table->unsignedInteger('skor');
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
        Schema::dropIfExists('nilai_formulir2s');
    }
}
