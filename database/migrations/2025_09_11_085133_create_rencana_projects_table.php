<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('rencana_projects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_project');
            $table->string('aktivitas');
            $table->integer('level');
            $table->unsignedBigInteger('parent')->nullable();
            $table->float('bobot');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->integer('minggu_ke')->nullable();
            $table->timestamps();

            // Jika parent mengacu ke id sendiri
            $table->foreign('parent')->references('id')->on('rencana_projects')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rencana_projects');
    }
}