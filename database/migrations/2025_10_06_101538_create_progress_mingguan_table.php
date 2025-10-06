<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('progress_mingguan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_project', 50);
            $table->string('aktivitas', 150);
            $table->integer('minggu_ke');
            $table->decimal('target', 5, 2)->nullable(); // target % dari rencana
            $table->decimal('realisasi', 5, 2)->nullable(); // input realisasi %
            $table->date('tanggal_realisasi')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['kode_project', 'minggu_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_mingguan');
    }
};
