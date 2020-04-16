<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJenisAnggotaColumnToReviewer1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviewer1s', function (Blueprint $table) {
            $table->enum('jenis_reviewer',['internal','eksternal']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviewer1s', function (Blueprint $table) {
            $table->dropColumn('jenis_reviewer');
        });
    }
}
