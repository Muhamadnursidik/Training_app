<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataliburnasionalTable extends Migration
{
    public function up()
    {
        Schema::create('dataliburnasional', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performance
            $table->index('tanggal');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dataliburnasional');
    }
}