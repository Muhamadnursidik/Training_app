<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_project', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mitra_id');
            $table->string('kode_project');
            $table->string('nama_project');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->timestamps();

            $table->foreign('mitra_id')
                ->references('id')
                ->on('data_mitra')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_project');
    }
};
