<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiFormulir3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_formulir3s', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id')->nullable();
            $table->unsignedInteger('formulir_id')->nullable();
            $table->string('reviewer_id')->nullable();
            $table->unsignedInteger('skor')->nullable();
            $table->string('total_skor')->nullable();
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
        Schema::dropIfExists('nilai_formulir3s');
    }
}
