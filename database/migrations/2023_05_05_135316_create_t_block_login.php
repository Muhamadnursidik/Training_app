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
        Schema::create('t_block_login', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key');
            $table->string('ip_address');
            $table->boolean('blokir')->default(true);
            $table->timestamps();

            $table->unique(['key', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_block_login');
    }
};
