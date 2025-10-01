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
        Schema::create('penyesuaian_rencana_projects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_addendum', 50)->index(); 
            $table->string('kode_project', 50)->index();
            $table->string('aktivitas', 255);
            $table->unsignedInteger('level')->nullable(); 
            $table->unsignedBigInteger('parent_id')->nullable(); 
            $table->decimal('bobot', 5, 2)->nullable()->comment('Persentase bobot (0-100)');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->unsignedInteger('minggu_ke')->nullable();
            $table->softDeletes(); 
            $table->timestamps();

            // Relasi ke parent row (self relation)
            $table->foreign('parent_id')->references('id')->on('penyesuaian_rencana_projects')->nullOnDelete();

            // Kalau perlu relasi ke tabel projects (kalau ada)
            // $table->foreign('kode_project')->references('kode_project')->on('projects')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_rencana_projects');
    }
};
