<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterUnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_unit_kerja', function (Blueprint $table) {
            $table->string('name');
            $table->string('code')->primary();
            $table->uuid('tipe_unit_kerja_id')->nullable();
            $table->boolean('status');
            $table->softDeletes();
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
        Schema::dropIfExists('master_unit_kerja');
    }
}
