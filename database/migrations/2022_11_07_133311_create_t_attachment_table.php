<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_attachment', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('source_id');
            $table->string('name')->nullable();
            $table->string('oriname');
            $table->string('module');
            
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
        Schema::dropIfExists('t_attachment');
    }
};
