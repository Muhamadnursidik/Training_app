<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMappingKonsolidasiUnitKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_konsolidasi_unit_kerja', function (Blueprint $table) {
            $table->uuid('parent_unit_kerja_id')->nullable();
            $table->uuid('unit_kerja_id')->nullable();
            $table->string('parent_unit_kerja_code')->nullable();
            $table->string('unit_kerja_code')->nullable();
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
        Schema::dropIfExists('mapping_konsolidasi_unit_kerja');
    }
}
