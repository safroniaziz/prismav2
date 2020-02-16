<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiFormulirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_formulirs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usulan_id');
            $table->unsignedInteger('formulir_id');
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
        Schema::dropIfExists('nilai_formulirs');
    }
}
