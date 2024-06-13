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
        Schema::create('t_data_history_review', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_id');
            $table->uuid('reviewer_id');
            $table->string('reviewer_name');
            $table->string('keterangan')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_data_history_review');
    }
};
