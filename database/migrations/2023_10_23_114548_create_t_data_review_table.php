<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_data_review', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_id')->nullable();
            $table->uuid('reviewer_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->nullable();
            $table->string('status')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data_review');
    }
};
