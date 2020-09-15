<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullValueToReviewer1sTable extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviewer1s', function (Blueprint $table) {
            $table->string('reviewer_nama')->nullable()->change();
            $table->string('reviewer_prodi_id')->nullable()->change();
            $table->string('reviewer_prodi_nama')->nullable()->change();
            $table->string('reviewer_fakultas_id')->nullable()->change();
            $table->string('reviewer_fakultas_nama')->nullable()->change();
            $table->string('ketua_peneliti_nidn')->nullable()->change();
            $table->string('reviewer_universitas')->nullable()->change();
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
            //
        });
    }
}
